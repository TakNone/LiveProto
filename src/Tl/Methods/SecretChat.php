<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Crypto\Aes;

use Tak\Liveproto\Errors\Security;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Enums\RekeyState;

use function Amp\File\getSize;

trait SecretChat {
	public function send_secret_message(
		string | int | object $peer,
		string $message,
		int $ttl,
		mixed ...$arguments
	) : object {
		$chat = $this->get_secret_chat($peer);
		$message = $this->secret->decryptedMessage(random_int(PHP_INT_MIN,PHP_INT_MAX),$ttl,$message,...$arguments);
		$data = $this->encrypt_secret_message($chat['id'],$message);
		$peer = $this->inputEncryptedChat(chat_id : $chat['id'],access_hash : $chat['access_hash']);
		return $this->messages->sendEncrypted(peer : $peer,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),data : $data);
	}
	public function send_secret_file(
		string | int | object $peer,
		object $file,
		string $message,
		int $ttl,
		mixed ...$arguments
	) : object {
		$chat = $this->get_secret_chat($peer);
		$message = $this->secret->decryptedMessage(random_int(PHP_INT_MIN,PHP_INT_MAX),$ttl,$message,...$arguments);
		$data = $this->encrypt_secret_message($chat['id'],$message);
		$peer = $this->inputEncryptedChat(chat_id : $chat['id'],access_hash : $chat['access_hash']);
		return $this->messages->sendEncryptedFile(peer : $peer,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),data : $data,file : $file);
	}
	public function send_secret_media(
		string | int | object $peer,
		string $path,
		string $caption,
		int $ttl
	) : object {
		list($file,$key,$iv) = $this->upload_secret_file($path);
		$mime = strval(mime_content_type($path));
		if(str_starts_with($mime,'image')):
			list($width,$height) = getimagesize($path);
			$media = $this->secret->decryptedMessageMediaPhoto(thumb : strval(null),thumb_w : 0,thumb_h : 0,w : $width,h : $height,size : getSize($path),key : $key,iv : $iv,caption : $caption);
		elseif(str_starts_with($mime,'video')):
			$media = $this->secret->decryptedMessageMediaVideo(thumb : strval(null),thumb_w : 0,thumb_h : 0,duration : $ttl,mime_type : $mime,w : 0,h : 0,size : getSize($path),key : $key,iv : $iv,caption : $caption);
		elseif(str_starts_with($mime,'audio')):
			# $media = $this->secret->decryptedMessageMediaAudio(duration : $ttl,mime_type : $mime,size : getSize($path),key : $key,iv : $iv);
			$attributes = array($this->secret->documentAttributeAudio(duration : $ttl));
			$media = $this->secret->decryptedMessageMediaDocument(thumb : strval(null),thumb_w : 0,thumb_h : 0,mime_type : $mime,size : getSize($path),key : $key,iv : $iv,attributes : $attributes,caption : $caption);
		else:
			$attributes = array($this->secret->documentAttributeFilename(file_name : $path));
			$media = $this->secret->decryptedMessageMediaDocument(thumb : strval(null),thumb_w : 0,thumb_h : 0,mime_type : $mime,size : getSize($path),key : $key,iv : $iv,attributes : $attributes,caption : $caption);
		endif;
		return $this->send_secret_file($peer,$file,$caption,$ttl,media : $media);
	}
	public function start_secret_chat(string | int | object $peer) : mixed {
		$user = $this->get_input_user($peer);
		$dhConfig = $this->getDhConfig();
		$a = gmp_import(random_bytes(0x100));
		$g_a = gmp_powm($dhConfig->g,$a,$dhConfig->p);
		Security::checkG(strval($g_a),strval($dhConfig->p),true);
		$result = $this->messages->requestEncryption(user_id : $user,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),g_a : gmp_export($g_a));
		$string = gmp_export($a);
		$auth_key = str_pad($string,0x100,chr(0),STR_PAD_LEFT);
		$this->set_secret(id : $result->id,access_hash : (isset($result->access_hash) ? $result->access_hash : 0),peer : $result->participant_id,auth_key : $auth_key,layer : $this->layer(secret : true),temp : true);
		return $result;
	}
	public function accept_secret_chat(object $chat) : object {
		# encryptedChatRequested#48f1d94c flags:# folder_id:flags.0?int id:int access_hash:long date:int admin_id:long participant_id:long g_a:bytes = EncryptedChat; #
		$dhConfig = $this->getDhConfig();
		$b = gmp_import(random_bytes(0x100));
		$g_a = gmp_import($chat->g_a);
		Security::checkG(strval($g_a),strval($dhConfig->p),true);
		$pow = gmp_powm($g_a,$b,$dhConfig->p);
		$string = gmp_export($pow);
		$auth_key = str_pad($string,0x100,chr(0),STR_PAD_LEFT);
		$writer = new Binary();
		$writer->write(substr(sha1($auth_key,true),-8));
		$keyfingerprint = $writer->readLong();
		$g_b = gmp_powm($dhConfig->g,$b,$dhConfig->p);
		Security::checkG(strval($g_b),strval($dhConfig->p),true);
		$peer = $this->inputEncryptedChat(chat_id : $chat->id,access_hash : $chat->access_hash);
		$result = $this->messages->acceptEncryption(peer : $peer,g_b : gmp_export($g_b),key_fingerprint : $keyfingerprint);
		$this->set_secret(id : $chat->id,access_hash : $chat->access_hash,peer : $chat->participant_id,auth_key : $auth_key,layer : $this->layer(secret : true));
		$this->notify_layer($chat->id);
		return $result;
	}
	public function finish_secret_chat_creation(object $chat) : void {
		# encryptedChat#61f0d4c7 id:int access_hash:long date:int admin_id:long participant_id:long g_a_or_b:bytes key_fingerprint:long = EncryptedChat; #
		$dhConfig = $this->getDhConfig();
		$g_a_or_b = gmp_import($chat->g_a_or_b);
		Security::checkG(strval($g_a_or_b),strval($dhConfig->p),true);
		$temp = $this->get_secret($chat->id);
		$a = gmp_import($temp['auth_key']);
		$pow = gmp_powm($g_a_or_b,$a,$dhConfig->p);
		$string = gmp_export($pow);
		$auth_key = str_pad($string,0x100,chr(0),STR_PAD_LEFT);
		$writer = new Binary();
		$writer->write(substr(sha1($auth_key,true),-8));
		$keyfingerprint = $writer->readLong();
		if($keyfingerprint !== $chat->key_fingerprint):
			throw new \LogicException('Invalid key fingerprint !');
		endif;
		$this->set_secret(id : $chat->id,access_hash : $chat->access_hash,peer : $chat->participant_id,auth_key : $auth_key,layer : $this->layer(secret : true),creator : true);
		$this->notify_layer($chat->id);
	}
	public function close_secret_chat(string | int | object $peer,mixed ...$arguments) : bool {
		$id = $this->remove_secret($peer);
		return $this->messages->discardEncryption($id,...$arguments);
	}
	public function getTTL(string | int | object $peer) : int {
		$chat = $this->get_secret_chat($peer);
		return intval($chat['ttl'] === 0 ? $this->messages->getDefaultHistoryTTL()->period : $chat['ttl']);
	}
	public function send_action(string | int | object $peer,object $action) : void {
		$chat = $this->get_secret_chat($peer);
		$message = $this->secret->decryptedMessageService(random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),action : $action);
		$data = $this->encrypt_secret_message($chat['id'],$message);
		$peer = $this->inputEncryptedChat(chat_id : $chat['id'],access_hash : $chat['access_hash']);
		$this->messages->sendEncryptedService(peer : $peer,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),data : $data);
	}
	public function notify_layer(int $chat_id) : void {
		$chat = $this->get_secret($chat_id);
		$layer = min($chat['layer'],$this->layer(secret : true));
		Logging::log('Secret Chat','Notify layer : '.strval($layer));
		$action = $this->secret->decryptedMessageActionNotifyLayer(layer : $layer);
		$this->send_action($chat_id,$action);
	}
	private function handle_decrypted_message(object $decrypted,object $message) : object | null {
		Logging::log('Secret Chat','handle decrypted message : '.get_class($decrypted));
		if($decrypted instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageService):
			Logging::log('Secret Chat','handle decrypted message action : '.get_class($decrypted->action));
			if($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionRequestKey):
				$this->accept_rekey($message->chat_id,$decrypted->action->exchange_id,$decrypted->action->g_a);
				return null;
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionAcceptKey):
				$this->commit_rekey($message->chat_id,$decrypted->action->exchange_id,$decrypted->action->g_b,$decrypted->action->key_fingerprint);
				return null;
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionCommitKey):
				$this->complete_rekey($message->chat_id,$decrypted->action->exchange_id,$decrypted->action->key_fingerprint);
				return null;
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionAbortKey):
				$this->abort_rekey($message->chat_id,$decrypted->action->exchange_id);
				return null;
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionNoop):
				return null;
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionResend):
				$chat = $this->get_secret($message->chat_id);
				$decrypted->action->start_seq_no -= intval($chat['creator'] === true);
				$decrypted->action->end_seq_no -= intval($chat['creator'] === true);
				$decrypted->action->start_seq_no >>= 1;
				$decrypted->action->end_seq_no >>= 1;
				for($seq = $decrypted->action->start_seq_no;$seq <= $decrypted->action->end_seq_no;$seq++):
					Logging::log('Secret Chat','attempt to resend seq no : '.$seq);
					$data = $this->encrypt_secret_message($message->chat_id,$chat['outgoing'][$seq]);
					$peer = $this->inputEncryptedChat(chat_id : $chat['id'],access_hash : $chat['access_hash']);
					$this->messages->sendEncrypted(peer : $peer,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),data : $data);
					Logging::log('Secret Chat','resend seq no : '.$seq);
				endfor;
				return null;
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionSetMessageTTL):
				$chat = $this->get_secret($message->chat_id);
				$chat['ttl'] = $decrypted->action->ttl_seconds;
				$this->set_secret(...$chat);
			elseif($decrypted->action instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageActionNotifyLayer):
				$chat = $this->get_secret($message->chat_id);
				$chat['layer'] = $decrypted->action->layer;
				$this->set_secret(...$chat);
				if(time() - $chat['created'] > 15):
					$this->notify_layer($message->chat_id);
				endif;
				return null;
			endif;
		elseif($decrypted instanceof \Tak\Liveproto\Tl\Types\Secret\DecryptedMessageLayer):
			$chat = $this->get_secret($message->chat_id);
			$chat['layer'] = $decrypted->layer;
			$chat['in_seq_no']++;
			$this->set_secret(...$chat);
			if(time() - $chat['created'] > 15):
				$this->notify_layer($message->chat_id);
			endif;
			return $this->handle_decrypted_message($decrypted->message,$message);
		endif;
		$decrypted->file = isset($message->file) ? $message->file : false;
		$decrypted->chat_id = $message->chat_id;
		$decrypted->date = $message->date;
		return $decrypted;
	}
	public function handle_encrypted_update(object $update) : object | null {
		# encryptedMessage#ed18c118 random_id:long chat_id:int date:int bytes:bytes file:EncryptedFile = EncryptedMessage; #
		# encryptedMessageService#23734b06 random_id:long chat_id:int date:int bytes:bytes = EncryptedMessage; #
		$chat = $this->get_secret($update->message->chat_id);
		$writer = new Binary();
		$writer->write(substr($update->message->bytes,0,8));
		$writer->write(substr(sha1($chat['auth_key'],true),-8));
		$authkeyid = $writer->readLong();
		$keyfingerprint = $writer->readLong();
		if($keyfingerprint !== $authkeyid):
			throw new \LogicException('Invalid key fingerprint !');
		endif;
		$msgKey = substr($update->message->bytes,8,16);
		list($key,$iv) = Helper::keyCalculate($chat['auth_key'],$msgKey,$chat['creator'] === false);
		$cipher = substr($update->message->bytes,24);
		$plain = Aes::decrypt($cipher,$key,$iv);
		$ourKey = hash('sha256',substr($chat['auth_key'],88 + ($chat['creator'] ? 8 : 0),32).$plain,true);
		if($msgKey !== substr($ourKey,8,16)) Logging::log('Decode Message','received msg key does not match with expected one !',E_ERROR);
		$plainReader = new Binary();
		$plainReader->write($plain);
		$msgLength = $plainReader->readInt();
		$message = $plainReader->read($msgLength);
		if($msgLength > strlen($plain)):
			throw new \LogicException('Message data length is too big !');
		elseif(strlen($plain) - $msgLength < 16):
			throw new \LogicException('Padding is too small !');
		elseif(strlen($plain) - $msgLength > 1024 + 4):
			throw new \LogicException('Padding is too big ! ');
		elseif(strlen($plain) % 16 !== 0):
			throw new \LogicException('Length of decrypted data is not divisible by 16');
		endif;
		$chat['ttr']--;
		$chat['incoming'][$chat['in_seq_no']] = $update->message;
		$this->set_secret(...$chat);
		if($chat['ttr'] <= 0 or $chat['updated'] < strtotime('- 1 week')):
			$this->rekey($update->message->chat_id);
		endif;
		$decryptedReader = new Binary();
		$decryptedReader->write($message);
		$decrypted = $decryptedReader->readObject();
		return $this->handle_decrypted_message($decrypted,$update->message);
	}
	public function encrypt_secret_message(int $chat_id,object $message) : string {
		$chat = $this->get_secret($chat_id);
		$chat['ttr']--;
		$messageLayer = $this->secret->decryptedMessageLayer(random_bytes : random_bytes(15 + 4 * rand(0,2)),layer : $chat['layer'],in_seq_no : $this->generateSecretInSeqNo($chat),out_seq_no : $this->generateSecretOutSeqNo($chat),message : $message);
		$chat['out_seq_no']++;
		$chat['outgoing'][$chat['out_seq_no']] = $message;
		$this->set_secret(...$chat);
		if($chat['ttr'] <= 0 or $chat['updated'] < strtotime('- 1 week')):
			$this->rekey($chat['id']);
		endif;
		$packet = $messageLayer->read();
		$plainWriter = new Binary();
		$plainWriter->writeInt(strlen($packet));
		$plainWriter->write($packet);
		$data = $plainWriter->read();
		$posmod = function(int $a,int $b) : int {
			$modulo = $a % $b;
			$padding = $modulo < 0 ? $modulo + abs($b) : $modulo;
			return $padding < 12 ? $padding + 16 : $padding;
		};
		$padding = random_bytes($posmod(-strlen($data),16));
		$msgKeyLarge = hash('sha256',substr($chat['auth_key'],88 + ($chat['creator'] ? 0 : 8),32).$data.$padding,true);
		$msgKey = substr($msgKeyLarge,8,16);
		list($key,$iv) = Helper::keyCalculate($chat['auth_key'],$msgKey,$chat['creator'] === true);
		$encrypt = Aes::encrypt($data.$padding,$key,$iv);
		$cipherWriter = new Binary();
		$cipherWriter->write(substr(sha1($chat['auth_key'],true),-8));
		$cipherWriter->write($msgKey);
		$cipherWriter->write($encrypt);
		return $cipherWriter->read();
	}
	private function rekey(int $chat_id) : void {
		$chat = $this->get_secret($chat_id);
		if($chat['rekey'] !== RekeyState::IDLE):
			return;
		endif;
		Logging::log('Secret Chat','rekey ...');
		$dhConfig = $this->getDhConfig();
		$a = gmp_import(random_bytes(0x100));
		$g_a = gmp_powm($dhConfig->g,$a,$dhConfig->p);
		Security::checkG(strval($g_a),strval($dhConfig->p),true);
		$e = random_int(PHP_INT_MIN,PHP_INT_MAX);
		$chat['rekey'] = RekeyState::REQUESTED;
		$chat['param'] = $a;
		$chat['exchangeid'] = $e;
		$this->set_secret(...$chat);
		$action = $this->secret->decryptedMessageActionRequestKey(exchange_id : $e,g_a : gmp_export($g_a));
		$this->send_action($chat_id,$action);
	}
	private function accept_rekey(int $chat_id,int $exchange_id,string $g_a) : void {
		$chat = $this->get_secret($chat_id);
		if($chat['rekey'] !== RekeyState::IDLE):
			if($chat['exchangeid'] > $exchange_id):
				return;
			elseif($chat['exchangeid'] === $exchange_id):
				$chat['rekey'] = RekeyState::IDLE;
				$this->set_secret(...$chat);
				return;
			endif;
		endif;
		Logging::log('Secret Chat','accept rekey ...');
		$dhConfig = $this->getDhConfig();
		$b = gmp_import(random_bytes(0x100));
		$g_a = gmp_import($g_a);
		Security::checkG(strval($g_a),strval($dhConfig->p),true);
		$pow = gmp_powm($g_a,$b,$dhConfig->p);
		$string = gmp_export($pow);
		$auth_key = str_pad($string,0x100,chr(0),STR_PAD_LEFT);
		$writer = new Binary();
		$writer->write(substr(sha1($auth_key,true),-8));
		$keyfingerprint = $writer->readLong();
		$chat['rekey'] = RekeyState::ACCEPTED;
		$chat['auth_key'] = $auth_key;
		$chat['exchangeid'] = $exchange_id;
		$chat['key_fingerprint'] = $keyfingerprint;
		$this->set_secret(...$chat);
		$g_b = gmp_powm($dhConfig->g,$b,$dhConfig->p);
		Security::checkG(strval($g_b),strval($dhConfig->p),true);
		$action = $this->secret->decryptedMessageActionAcceptKey(exchange_id : $exchange_id,g_b : gmp_export($g_b),key_fingerprint : $keyfingerprint);
		$this->send_action($chat_id,$action);
	}
	private function commit_rekey(int $chat_id,int $exchange_id,string $g_b,int $key_fingerprint) : void {
		$chat = $this->get_secret($chat_id);
		if($chat['rekey'] !== RekeyState::REQUESTED or $chat['exchangeid'] !== $exchange_id):
			$chat['rekey'] = RekeyState::IDLE;
			$this->set_secret(...$chat);
			return;
		endif;
		Logging::log('Secret Chat','commit rekey ...');
		$dhConfig = $this->getDhConfig();
		$g_b = gmp_import($g_b);
		Security::checkG(strval($g_b),strval($dhConfig->p),true);
		$pow = gmp_powm($g_b,$chat['param'],$dhConfig->p);
		$string = gmp_export($pow);
		$auth_key = str_pad($string,0x100,chr(0),STR_PAD_LEFT);
		$writer = new Binary();
		$writer->write(substr(sha1($auth_key,true),-8));
		$keyfingerprint = $writer->readLong();
		if($keyfingerprint !== $key_fingerprint):
			$action = $this->secret->decryptedMessageActionAbortKey(exchange_id : $exchange_id);
			$message = $this->secret->decryptedMessageService(random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),action : $action);
			$data = $this->encrypt_secret_message($chat_id,$message);
			$peer = $this->inputEncryptedChat(chat_id : $chat['id'],access_hash : $chat['access_hash']);
			$this->messages->sendEncryptedService(peer : $peer,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),data : $data);
			throw new \LogicException('Invalid key fingerprint !');
		endif;
		unset($chat['param']);
		$chat['rekey'] = RekeyState::IDLE;
		$chat['ttr'] = 100;
		$chat['updated'] = time();
		$chat['auth_key'] = $auth_key;
		$chat['key_fingerprint'] = $keyfingerprint;
		$this->set_secret(...$chat);
		$action = $this->secret->decryptedMessageActionCommitKey(exchange_id : $exchange_id,key_fingerprint : $keyfingerprint);
		$this->send_action($chat_id,$action);
	}
	private function complete_rekey(int $chat_id,int $exchange_id,int $key_fingerprint) : void {
		$chat = $this->get_secret($chat_id);
		if($chat['rekey'] !== RekeyState::ACCEPTED or $chat['exchangeid'] !== $exchange_id):
			return;
		endif;
		Logging::log('Secret Chat','complete rekey ...');
		if($chat['key_fingerprint'] !== $key_fingerprint):
			$action = $this->secret->decryptedMessageActionAbortKey(exchange_id : $exchange_id);
			$message = $this->secret->decryptedMessageService(random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),action : $action);
			$data = $this->encrypt_secret_message($chat_id,$message);
			$peer = $this->inputEncryptedChat(chat_id : $chat['id'],access_hash : $chat['access_hash']);
			$this->messages->sendEncryptedService(peer : $peer,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),data : $data);
			throw new \LogicException('Invalid key fingerprint !');
		endif;
		$chat['rekey'] = RekeyState::IDLE;
		$chat['ttr'] = 100;
		$chat['updated'] = time();
		$this->set_secret(...$chat);
		$action = $this->secret->decryptedMessageActionNoop();
		$this->send_action($chat_id,$action);
	}
	private function abort_rekey(int $chat_id,int $exchange_id) : void {
		$chat = $this->get_secret($chat_id);
		if($chat['rekey'] === RekeyState::IDLE or $chat['exchangeid'] !== $exchange_id):
			return;
		endif;
		Logging::log('Secret Chat','abort rekey ...');
		unset($chat['param']);
		$chat['exchangeid'] = 0;
		$this->set_secret(...$chat);
	}
	private function generateSecretInSeqNo(array $chat) : int {
		return intval($chat['in_seq_no'] * 2 + intval($chat['creator'] === false));
	}
	private function generateSecretOutSeqNo(array $chat) : int {
		return intval($chat['out_seq_no'] * 2 + intval($chat['creator'] === true));
	}
	public function get_secret_chat(string | int | object $peer) :  array {
		$hash = md5(serialize($peer));
		$this->secretChats = array_slice(array : $this->secretChats,offset : -200,preserve_keys : true);
		if(key_exists($hash,$this->secretChats) === false):
			try {
				$this->secretChats[$hash] = $this->get_peer_id($peer);
			} catch(\Throwable){
				/* You may get an `CHAT_ID_INVALID` error , Ignore it */
				try {
					$this->secretChats[$hash] = intval($peer);
				} catch(\Throwable){
					throw new \InvalidArgumentException('Peer not found !');
				}
			}
		endif;
		return $this->get_secret($this->secretChats[$hash]);
	}
	public function remove_secret_chat(string | int | object $peer) : void {
		$chat = $this->get_secret_chat(peer : $peer);
		$this->load->peers->deletePeer(type : 'secrets',by : 'id',what : $chat['id']);
	}
	public function get_secret(int $chat_id) : array {
		foreach(['id','peer'] as $by):
			if($secret = $this->load->peers->getPeer(type : 'secrets',by : $by,what : $chat_id)):
				return $secret;
			endif;
		endforeach;
		throw new \RuntimeException('Chat '.strval($chat_id).' not found !');
	}
	public function set_secret(mixed ...$arguments) : void {
		$correct = array_filter($arguments,is_string(...),ARRAY_FILTER_USE_KEY);
		$correct += ['rekey'=>RekeyState::IDLE,'ttl'=>0,'ttr'=>100,'updated'=>time(),'created'=>time(),'creator'=>false,'temp'=>false,'in_seq_no'=>0,'out_seq_no'=>0,'incoming'=>[],'outgoing'=>[]];
		$this->load->peers->setPeers(type : 'secrets',peers : array($correct));
	}
}

?>