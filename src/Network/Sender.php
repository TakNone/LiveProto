<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Crypto\Aes;

use Tak\Liveproto\Errors\RpcError;

use Tak\Liveproto\Errors\Security;

use Tak\Liveproto\Errors\TransportError;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Utils\Settings;

use Tak\Liveproto\Tl\All;

use Tak\Liveproto\Tl\MTRequest;

use Tak\Liveproto\Parser\Tl;

use Tak\Liveproto\Enums\NonRpcResult;

use Tak\Liveproto\Enums\MTProtoKeepAlive;

use Amp\TimeoutCancellation;

use Amp\Sync\LocalMutex;

use Revolt\EventLoop;

use RuntimeException;

use Throwable;

final class Sender {
	protected readonly object $load;
	private array $salts = array();
	private array $pendingAcks = array();
	private int $lastAckTime = 0;
	private array $received = array();
	private array $queue = array();
	private string $receiveLoop;

	public const UPDATES = array(0xe317af7e,0x313bc7f8,0x4d6deea5,0x78d4dec1,0x725b04c3,0x74ae4240,0x9015e101);

	public const BY_MSG_ID = 'messageId';
	public const BY_IDENTIFIERS = 'identifier';
	public const BY_SEQ_NO = 'sequence';

	public function __construct(protected object $transport,private readonly object $session,private object $handler,private readonly MTProtoKeepAlive $keepAlive){
		$this->load = $session->load();
		$session->reset();
		$this->receiveLoop = strval(null);
		$this->receiveLoop = EventLoop::defer($this->receivePacket(...));
		EventLoop::setErrorHandler($this->errors(...));
		gc_enable();
	}
	public function send(MTRequest $request) : void {
		if($this->keepAlive === MTProtoKeepAlive::HTTP_LONG_POLL):
			$httpWait = new MTRequest('types.httpWait');
			$httpRequest = $httpWait->withParameters(max_delay : Settings::envGuess('HTTP_MAX_DELAY',1000),wait_after : Settings::envGuess('HTTP_WAIT_AFTER',200),max_wait : Settings::envGuess('HTTP_MAX_WAIT',2000));
			EventLoop::queue($this->sendContainer(...),$request,$httpRequest);
		else:
			EventLoop::queue($this->sendPacket(...),$request);
		endif;
	}
	# https://core.telegram.org/mtproto/service_messages_about_messages#acknowledgment-of-receipt #
	public function sendAcknowledgement() : void {
		$acks = array_unique($this->pendingAcks);
		$elapsed = intval(time() - $this->lastAckTime);
		if($acks and (count($acks) >= 0x10 or (60 <= $elapsed and $elapsed <= 120))):
			$msgsAck = new MTRequest('types.msgsAck',contentRelated : false);
			$ackRequest = $msgsAck->withParameters(msg_ids : $acks);
			$this->send($ackRequest);
			$this->pendingAcks = array();
			$this->lastAckTime = time();
		endif;
	}
	# https://core.telegram.org/mtproto/service_messages#containers #
	public function sendContainer(MTRequest ...$requests) : void {
		# msg_container#73f1f8dc messages:vector<message> = MessageContainer; #
		if(empty($requests) === false):
			$container = new Binary(true);
			$container->writeInt(0x73f1f8dc);
			$container->writeInt(count($requests));
			foreach($requests as $request):
				$message_id = is_null($request->messageId) ? $this->session->getNewMsgId() : $request->messageId;
				$this->queue[strval($request)] = $request;
				$sequence = $this->session->generateSequence($request->contentRelated);
				$container->write($request->toMessage($message_id,$sequence));
			endforeach;
			$mtRequest = new MTRequest($container,contentRelated : false);
			$this->sendPacket($mtRequest);
		endif;
	}
	public function sendPacket(MTRequest $request) : void {
		$message_id = is_null($request->messageId) ? $this->session->getNewMsgId() : $request->messageId;
		$sequence = is_null($request->sequence) ? $this->session->generateSequence($request->contentRelated) : $request->sequence;
		$data = $this->composePlainMessage(request : $request,salt : $this->getFreshFutureSalt(renew : $request->keepOldSalt === false),session_id : $this->load->id,message_id : $message_id,sequence : $sequence);
		$message = $this->encryptMTProtoMessage(data : $data,version : 2);
		$request->messageId = $message_id;
		$this->queue[strval($request)] = $request;
		Logging::log('Send Packet','Request : '.strval($request->getBinary()).' , Packet length : '.strlen($message).' , Message ID : '.$message_id.' , Sequence : '.$sequence.' , Identifier Type : '.var_export($request->identifier,true));
		$this->transport->send($message);
	}
	public function composePlainMessage(MTRequest $request,int $salt,int $session_id,int $message_id,int $sequence) : string {
		$plainWriter = new Binary();
		$plainWriter->writeLong($salt);
		$plainWriter->writeLong($session_id);
		$plainWriter->write($request->toMessage($message_id,$sequence));
		return $plainWriter->read();
	}
	public function encryptMTProtoMessage(string $data,int $version) : string {
		if($version === 1):
			$msgKeyLarge = sha1($data,true);
			$msgKey = substr($msgKeyLarge,4,16);
			list($key,$iv) = Helper::aesCalculate($this->load->auth_key->key,$msgKey,true);
		elseif($version === 2):
			$fmod = fn(int $a,int $b) : int => ($a % $b + $b) % $b;
			$data .= random_bytes($fmod(-(strlen($data) + 12),16) + 12);
			$msgKeyLarge = hash('sha256',substr($this->load->auth_key->key,88,32).$data,true);
			$msgKey = substr($msgKeyLarge,8,16);
			list($key,$iv) = Helper::keyCalculate($this->load->auth_key->key,$msgKey,true);
		else:
			throw new \InvalidArgumentException('The MTProto version argument is invalid !');
		endif;
		$encrypt = Aes::encrypt($data,$key,$iv);
		$cipherWriter = new Binary();
		$cipherWriter->writeLong($this->load->auth_key->id);
		$cipherWriter->write($msgKey);
		$cipherWriter->write($encrypt);
		return $cipherWriter->read();
	}
	# https://core.telegram.org/api/pfs#related-articles #
	public function bindTempAuthKey(self $sender,int $try = 3) : bool {
		$nonce = Helper::generateRandomLong();
		$authKeyInner = new MTRequest('types.bindAuthKeyInner');
		$bindInner = $authKeyInner->withParameters(nonce : $nonce,temp_auth_key_id : $sender->load->auth_key->id,perm_auth_key_id : $this->load->auth_key->id,temp_session_id : $sender->load->id,expires_at : $sender->load->auth_key->expires_at);
		do {
			try {
				$message_id = $sender->load->last_msg_id = $this->session->getNewMsgId();
				$data = $this->composePlainMessage(request : $bindInner,salt : Helper::generateRandomLong(),session_id : Helper::generateRandomLong(),message_id : $message_id,sequence : 0);
				$cipher = $this->encryptMTProtoMessage(data : $data,version : 1);
				Logging::log('Bind Temp','Expires at : '.strval($sender->load->auth_key->expires_at).' , EncryptedMessage ID : '.$message_id);
				$bindTemp = new MTRequest('functions.auth.bindTempAuthKey',messageId : $message_id,timeout : 10);
				$bindRequest = $bindTemp->withParameters(perm_auth_key_id : $this->load->auth_key->id,nonce : $nonce,expires_at : $sender->load->auth_key->expires_at,encrypted_message : $cipher);
				return $sender($bindRequest);
			} catch(RpcError $error){
				$code = $error->getCode();
			} finally {
				$try--;
			}
		} while($try > 0 and $code === 400);
		throw new RuntimeException('Failed to create a temporary client !',$code,$error);
	}
	public function receive(MTRequest $request) : mixed {
		$future = $request->getDeferred()->getFuture();
		$cancellation = $request->timeout > 0 ? new TimeoutCancellation($request->timeout) : $request->cancellation;
		return $future->await($cancellation);
	}
	public function receivedLoop() : void {
		static $mutex = new LocalMutex;
		$lock = $mutex->acquire();
		foreach($this->received as $hash => $object):
			if(array_key_exists($hash,$this->queue)):
				$request = $this->queue[$hash];
				$deferred = $request->getDeferred();
				if($deferred->isComplete() === false):
					switch($object->status):
						case 'success':
							if(isset($object->result->chats,$object->result->users) and is_array($object->result->chats) and is_array($object->result->users)):
								$this->handler->saveAccessHash($object->result);
							endif;
							if(isset($object->result->vector) and is_callable($object->result->vector)):
								$constructor = $request->getConstructor();
								$comments = Tl::parseDocComment($constructor);
								$return = Tl::parseType($comments['return']);
								$object->result = call_user_func($object->result->vector,$return['type'],true);
							endif;
							if(isset($object->result->bool) and is_callable($object->result->bool)):
								$object->result = call_user_func($object->result->bool,true);
							endif;
							$deferred->complete($object->result);
							break;
						case 'error':
							$deferred->error($object->exception);
							break;
						case 'resend':
							$this->sendPacket($request);
							$request->keepOldSalt || $this->httpLongPoll();
							break;
					endswitch;
					unset($this->received[$hash]);
				endif;
			endif;
		endforeach;
		$lock->release();
	}
	public function receivePacket() : void {
		while(isset($this->receiveLoop)):
			try {
				$body = $this->transport->receive();
				$closure = function(string $result) : void {
					$plain = $this->decryptMTProtoMessage(data : $result,version : 2);
					list($reader,$salt,$session_id,$message_id,$sequence) = $this->decomposePlainMessage($plain);
					if($session_id !== $this->load->id):
						Logging::log('Receive Packet','Server replied with a wrong session id !',E_ERROR);
					endif;
					if($message_id % 2 !== 1):
						Logging::log('Receive Packet','Server sent an even message id !',E_ERROR);
					endif;
					try {
						$this->processMessage($reader,$message_id,$sequence);
						$this->receivedLoop();
						$this->gcCleanup();
					} catch(Throwable $error){
						Logging::log('Process Message',$error->getMessage(),E_WARNING);
					}
				};
				EventLoop::queue($closure,$body);
			} catch(Security $error){
				Logging::log('Security',$error->getMessage(),E_NOTICE);
			} catch(TransportError $error){
				Logging::log('Transport Error',$error->getMessage(),E_ERROR);
			} catch(RuntimeException $error){
				Logging::log('Connection Error',$error->getMessage(),E_ERROR);
				$this->close();
			} catch(Throwable $error){
				Logging::log('Receive Packet',$error->getMessage(),E_WARNING);
				$this->ping();
				$this->httpLongPoll();
			}
		endwhile;
	}
	public function decryptMTProtoMessage(string $data,int $version) : string {
		$reader = new Binary();
		$reader->write($data);
		if(strlen($data) < 8):
			Logging::log('Decode Message','Body length is less than 8 !',E_NOTICE);
		endif;
		$remoteAuthKeyId = $reader->readLong();
		if($remoteAuthKeyId !== $this->load->auth_key->id):
			Logging::log('Decode Message','Server replied with an invalid auth key !',E_ERROR);
		endif;
		$msgKey = $reader->read(16);
		if($version === 1):
			list($key,$iv) = Helper::aesCalculate($this->load->auth_key->key,$msgKey,false);
		else:
			list($key,$iv) = Helper::keyCalculate($this->load->auth_key->key,$msgKey,false);
		endif;
		$cipher = $reader->read();
		$plain = Aes::decrypt($cipher,$key,$iv);
		if($version === 1):
			$ourKey = sha1($plain,true);
			$ourKey = substr($ourKey,4,16);
		else:
			$ourKey = hash('sha256',substr($this->load->auth_key->key,96,32).$plain,true);
			$ourKey = substr($ourKey,8,16);
		endif;
		if($msgKey !== $ourKey):
			Logging::log('Decode Message','Received msg key does not match with expected one !',E_ERROR);
		endif;
		return strval($plain);
	}
	public function decomposePlainMessage(string $plain) : array {
		$plainReader = new Binary();
		$plainReader->write($plain);
		$salt = $plainReader->readLong();
		$session_id = $plainReader->readLong();
		$mtResponse = MTRequest::fromMessage($plainReader);
		$padding = strlen($plainReader->read());
		if($padding < 12 or $padding > 1024):
			Logging::log('Decode Message','Padding must be between 12 and 1024 bytes !',E_ERROR);
		endif;
		return array($mtResponse->binary,$salt,$session_id,$mtResponse->messageId,$mtResponse->sequence);
	}
	public function processMessage(Binary $reader,int $message_id,int $sequence) : void {
		$object = strval($reader);
		$constructorId = $reader->readInt();
		Logging::log('Process Message',(class_exists($object) ? 'Object : '.$object : 'Constructor Number : 0x'.dechex($constructorId)).' , Message ID : '.$message_id.' , Sequence : '.$sequence);
		# pong#347773c5 msg_id:long ping_id:long = Pong; #
		if($constructorId == 0x347773c5):
			$msg_id = $reader->readLong();
			$ping_id = $reader->readLong();
			if($hash = $this->getHashQueue($msg_id,self::BY_MSG_ID)):
				$this->received[$hash] = (object) ['status'=>'success','result'=>$ping_id];
				Logging::log('Live','Pong !');
			endif;
			return;
		# msg_container is used instead of msg_copy #
		# msg_container#73f1f8dc messages:vector<message> = MessageContainer; #
		elseif($constructorId == 0x73f1f8dc):
			$count = $reader->readInt();
			# message msg_id:long seqno:int bytes:int body:Object = Message; #
			for($i = 0;$i < $count;$i++):
				$msg_id = $reader->readLong();
				$seq_no = $reader->readInt();
				$length = $reader->readInt();
				$position = $reader->tellPosition();
				$this->processMessage($reader,$msg_id,$seq_no);
				$reader->setPosition($length + $position);
			endfor;
			return;
		# gzip_packed#3072cfa1 packed_data:string = Object; #
		elseif($constructorId == 0x3072cfa1):
			$packed_data = $reader->readBytes();
			$unpacked = gzdecode($packed_data);
			$gzip = new Binary();
			$gzip->write($unpacked);
			$this->processMessage($gzip,$message_id,$sequence);
			return;
		# msgs_ack#62d6b459 msg_ids:Vector<long> = MsgsAck; #
		elseif($constructorId == 0x62d6b459):
			$msg_ids = $reader->readVector('long');
			Logging::log('Msgs Ack',implode(chr(0x20).chr(0x2c).chr(0x20),$msg_ids));
			return;
		# rpc_result#f35c6d01 req_msg_id:long result:Object = RpcResult; #
		elseif($constructorId == 0xf35c6d01):
			$req_msg_id = $reader->readLong();
			if($hash = $this->getHashQueue($req_msg_id,self::BY_MSG_ID)):
				$constructorId = $reader->readInt();
				# gzip_packed#3072cfa1 packed_data:string = Object; #
				if($constructorId === 0x3072cfa1):
					$packed_data = $reader->readBytes();
					$unpacked = gzdecode($packed_data);
					$reader = new Binary();
					$reader->write($unpacked);
					$constructorId = $reader->readInt();
				endif;
				# rpc_error#2144ca19 error_code:int error_message:string = RpcError; #
				if($constructorId === 0x2144ca19):
					$error_code = $reader->readInt();
					$error_message = $reader->readBytes();
					Logging::log('RPC',$error_code.chr(32).$error_message,E_ERROR);
					$this->received[$hash] = (object) ['status'=>'error','exception'=>new RpcError($error_message,$error_code)];
				# rpc_answer_unknown#5e2ad36e = RpcDropAnswer; #
				elseif($constructorId === 0x5e2ad36e):
					# nothing ! #
				# rpc_answer_dropped_running#cd78e586 = RpcDropAnswer; #
				elseif($constructorId === 0xcd78e586):
					# again nothing ! #
				# rpc_answer_dropped#a43ad8b7 msg_id:long seq_no:int bytes:int = RpcDropAnswer; #
				elseif($constructorId === 0xa43ad8b7):
					$msg_id = $reader->readLong();
					$seq_no = $reader->readInt();
					$bytes = $reader->readInt();
				else:
					$result = $reader->readObject(true);
					$this->received[$hash] = (object) ['status'=>'success','result'=>$result];
					if(in_array($constructorId,self::UPDATES)):
						$this->handler->processUpdate($result);
					endif;
				endif;
			endif;
		# new_session_created#9ec20908 first_msg_id:long unique_id:long server_salt:long = NewSession #
		elseif($constructorId == 0x9ec20908):
			$first_msg_id = $reader->readLong();
			$unique_id = $reader->readLong();
			$server_salt = $reader->readLong();
			$this->load['salt'] = $server_salt;
			$this->load['salt_valid_until'] = strtotime('+ 30 minutes');
			Logging::log('New Session Created','First Message ID : '.$first_msg_id);
		# bad_msg_notification#a7eff811 bad_msg_id:long bad_msg_seqno:int error_code:int = BadMsgNotification; #
		elseif($constructorId == 0xa7eff811):
			$bad_msg_id = $reader->readLong();
			if($hash = $this->getHashQueue($bad_msg_id,self::BY_MSG_ID)):
				$status_msg = (object) ['status'=>'resend'];
				$bad_msg_seqno = $reader->readInt();
				$error_code = $reader->readInt();
				if(in_array($error_code,array(16,17))):
					$this->session->updateTimeOffset($message_id);
				elseif($error_code == 18):
					$this->load['sequence'] = intval(ceil($this->load['sequence'] / 4) * 4);
				elseif(in_array($error_code,array(32,33))):
					$this->session->reset();
				elseif(in_array($error_code,array(34,35))):
					$this->load['sequence'] += 1;
				else:
					$status_msg = (object) ['status'=>'error','exception'=>new RpcError('Bad Msg Notification !',$error_code)];
				endif;
				$this->queue[$hash]->messageId = $this->session->getNewMsgId();
				$this->queue[$hash]->sequence = $this->session->generateSequence($this->queue[$hash]->contentRelated);
				$this->received[$hash] = $status_msg;
				Logging::log('Bad Msg Notification','Bad Message ID : '.$bad_msg_id.' , Error Code : '.$error_code);
			endif;
		# bad_server_salt#edab447b bad_msg_id:long bad_msg_seqno:int error_code:int new_server_salt:long = BadMsgNotification; #
		elseif($constructorId == 0xedab447b):
			$bad_msg_id = $reader->readLong();
			if($hash = $this->getHashQueue($bad_msg_id,self::BY_MSG_ID)):
				$bad_msg_seqno = $reader->readInt();
				$error_code = $reader->readInt(); // 48: incorrect server salt (in this case, the bad_server_salt response is received with the correct salt, and the message is to be re-sent with it) //
				$new_server_salt = $reader->readLong();
				$this->load['salt'] = $new_server_salt;
				$this->load['salt_valid_until'] = strtotime('+ 30 minutes');
				$this->queue[$hash]->sequence = $this->session->generateSequence($this->queue[$hash]->contentRelated);
				$this->received[$hash] = (object) ['status'=>'resend'];
				Logging::log('Bad Server Salt','Bad Message ID : '.$bad_msg_id);
			endif;
		# msg_detailed_info#276d3ec6 msg_id:long answer_msg_id:long bytes:int status:int = MsgDetailedInfo; #
		elseif($constructorId == 0x276d3ec6):
			$msg_id = $reader->readLong();
			$answer_msg_id = $reader->readLong();
			$bytes = $reader->readInt();
			$status = $reader->readInt();
			$this->pendingAcks []= $answer_msg_id;
		# msg_new_detailed_info#809db6df answer_msg_id:long bytes:int status:int = MsgDetailedInfo; #
		elseif($constructorId == 0x809db6df):
			$answer_msg_id = $reader->readLong();
			$bytes = $reader->readInt();
			$status = $reader->readInt();
			$this->pendingAcks []= $answer_msg_id;
		# future_salts#ae500895 req_msg_id:long now:int salts:vector<future_salt> = FutureSalts; #
		elseif($constructorId == 0xae500895):
			$req_msg_id = $reader->readLong();
			if($hash = $this->getHashQueue($req_msg_id,self::BY_MSG_ID)):
				$now = $reader->readInt();
				$count = $reader->readInt();
				# future_salt#0949d9dc valid_since:int valid_until:int salt:long = FutureSalt; #
				for($i = 0;$i < $count;$i++):
					$valid_since = $reader->readInt();
					$valid_until = $reader->readInt();
					$salt = $reader->readLong();
					$salts []= (object) ['valid_since'=>$valid_since,'valid_until'=>$valid_until,'salt'=>$salt];
				endfor;
				$this->received[$hash] = (object) ['status'=>'success','result'=>$salts];
				Logging::log('Future Salts','Number of salts : '.count($salts));
			endif;
		# destroy_session_ok#e22045fc session_id:long = DestroySessionRes; #
		# destroy_session_none#62d350c9 session_id:long = DestroySessionRes; #
		elseif(in_array($constructorId,array(0xe22045fc,0x62d350c9))):
			$result = $reader->readObject(true);
			if($hash = $this->getHashQueue(NonRpcResult::DESTROY_SESSION,self::BY_IDENTIFIERS)):
				$this->received[$hash] = (object) ['status'=>'success','result'=>$result];
			endif;
			Logging::log('Destroy Session Res','Session Id : '.$result->session_id);
		# destroy_auth_key_ok#f660e1d4 = DestroyAuthKeyRes; #
		# destroy_auth_key_none#0a9f2259 = DestroyAuthKeyRes; #
		# destroy_auth_key_fail#ea109b13 = DestroyAuthKeyRes; #
		elseif(in_array($constructorId,array(0xf660e1d4,0x0a9f2259,0xea109b13))):
			$result = $reader->readObject(true);
			if($hash = $this->getHashQueue(NonRpcResult::DESTROY_AUTH_KEY,self::BY_IDENTIFIERS)):
				$this->received[$hash] = (object) ['status'=>'success','result'=>$result];
			endif;
			Logging::log('Destroy Auth',$result->getClass(),E_WARNING);
		elseif(in_array($constructorId,self::UPDATES)):
			$this->handler->processUpdate($reader->readObject(true));
		else:
			Logging::log('Process Message','Unknown message : 0x'.dechex($constructorId),E_WARNING);
			var_dump($reader->readObject(true));
		endif;
		if($sequence % 2 === 1):
			$this->pendingAcks []= $message_id;
			$this->sendAcknowledgement();
		endif;
	}
	public function getFutureSalts() : array {
		$valid_since = strtotime('+ 36 hours');
		$valid_until = max($this->load->salt_valid_until,strtotime('+ 1 minute'));
		$this->salts = array_filter($this->salts,fn(object $salt) : bool => $salt->valid_since <= $valid_since and $salt->valid_until >= $valid_until);
		if(count($this->salts) <= Settings::envGuess('MIN_VALID_SALTS',0x0)):
			Logging::log('Future Salts','Requesting new future salts from server ...');
			$getSalts = new MTRequest('functions.getFutureSalts',keepOldSalt : true);
			$getSaltsRequest = $getSalts->withParameters(num : Settings::envGuess('FUTURE_SALTS_COUNT',0x20));
			$salts = $this($getSaltsRequest);
			$this->salts = array_merge($this->salts,$salts);
			return $this->getFutureSalts();
		endif;
		return $this->salts;
	}
	public function getFreshFutureSalt(bool $renew = true) : int {
		if($renew and $this->load->salt_valid_until < strtotime('+ 1 minute')):
			$salts = $this->getFutureSalts();
			$future_salt = reset($salts);
			list($this->load->salt,$this->load->salt_valid_until) = array($future_salt->salt,$future_salt->valid_until);
			Logging::log('Fresh Future Salt','A new futures salt replaced the previous one , valid for up to '.intval($future_salt->valid_until - time()).' seconds');
		endif;
		return $this->load->salt;
	}
	public function destroySession(int $session_id) : ? object {
		if(isset($this->receiveLoop) and $session_id !== 0):
			$destroy = new MTRequest('functions.destroySession',identifier : NonRpcResult::DESTROY_SESSION);
			$destroyRequest = $destroy->withParameters(session_id : $session_id);
			$result = $this($destroyRequest);
			Logging::log('Destroy Session','Session Id : '.$session_id.' , Result : '.$result->getClass());
			return $result;
		else:
			return null;
		endif;
	}
	public function destroyAuthKey(float $timeout = 1.0) : bool | object {
		if(isset($this->receiveLoop)):
			try {
				$destroyRequest = new MTRequest('functions.destroyAuthKey',identifier : NonRpcResult::DESTROY_AUTH_KEY);
				$result = $this($destroyRequest);
				Logging::log('Destroy Auth','Result received with '.$timeout.' second timeout');
				return $result;
			} catch(Throwable $error){
				return true;
			}
		else:
			return false;
		endif;
	}
	public function ping() : void {
		if(isset($this->receiveLoop) and $this->keepAlive === MTProtoKeepAlive::PING_PONG):
			Logging::log('Live','Ping ...');
			$ping = new MTRequest('functions.pingDelayDisconnect');
			$pingRequest = $ping->withParameters(ping_id : Helper::generateRandomLong(),disconnect_delay : 75);
			$this->sendPacket($pingRequest);
		endif;
	}
	public function httpLongPoll() : void {
		if(isset($this->receiveLoop) and $this->keepAlive === MTProtoKeepAlive::HTTP_LONG_POLL):
			Logging::log('Live','Http Wait ...');
			$httpWait = new MTRequest('types.httpWait');
			$httpRequest = $httpWait->withParameters(max_delay : 0,wait_after : 0,max_wait : 75 * 1000);
			$this->sendPacket($httpRequest);
		endif;
	}
	public function getHashQueue(mixed $value,string $by) : ? string {
		$filtered = array_filter($this->queue,fn(MTRequest $request) : bool => $request->$by === $value);
		return array_key_first($filtered);
	}
	public function gcCleanup() : void {
		/*
		 * TODO : Let's try not to remove those who are waiting to receive a response from the queue
		 *
		 * $filtered = array_filter($this->queue,fn(MTRequest $request) : bool => is_null($request->getDeferred(false)));
		 */
		$before = count($this->queue);
		$this->queue = array_filter($this->queue,fn(MTRequest $request) : bool => $request->getDeferred(lazyInit : false)?->isComplete() == false);
		$this->queue = array_slice(array : $this->queue,offset : - Settings::envGuess('MAX_QUEUE_LENGTH',1000),preserve_keys : true);
		$after = count($this->queue);
		if($before - $after > 0):
			Logging::log('Garbage Collection','Cleanup : '.strval($before - $after));
			gc_collect_cycles();
		endif;
	}
	public function errors(Throwable $error) : never {
		Logging::log('Sender',$error->getMessage(),E_ERROR);
		throw $error;
	}
	public function close() : void {
		unset($this->receiveLoop);
		Logging::log('Sender','Closed !',E_WARNING);
	}
	public function __invoke(MTRequest $raw) : mixed {
		$this->sendPacket($raw);
		/*
		 * Requests passing through here should not require HTTP_LONG_POLL
		 * Otherwise a function like getFutureSalts would be looking for a new salt in a recursive loop
		 * http_wait requires a `salt` when sending , but we haven't received a new `salt` result to assign to it yet
		 * Away from the container
		 */
		$raw->keepOldSalt || $this->httpLongPoll();
		return $this->receive($raw);
	}
	public function __destruct(){
		$this->close();
	}
}

?>