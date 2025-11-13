<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Handlers;

use Tak\Liveproto\Utils\Instance;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Filters\Filter;

use Amp\DeferredFuture;

use Amp\DeferredCancellation;

use Amp\TimeoutException;

use Amp\Sync\LocalMutex;

use Amp\Sync\Lock;

use Revolt\EventLoop;

use Closure;

use Throwable;

use function Amp\async;

use function Amp\delay;

final class Updates {
	public readonly object $load;
	private array $handlers = array();
	private array $channelsPts = array();
	protected LocalMutex $mutex;
	protected Lock $lock;

	public function __construct(public readonly object $client,private readonly object $session){
		$this->load = $session->load();
		$this->mutex = new LocalMutex;
	}
	public function addEventHandler(object | callable $callback,? string $unique = null,object | array ...$filters) : void {
		if(is_object($callback) and is_a($callback,'Closure') === false):
			$functions = Filter::getFunctions($callback,$unique);
			$this->handlers = array_merge($this->handlers,$functions);
		else:
			$function = Filter::getFunction($callback,$unique);
			$additional = array_map(fn(object | array $filter) : array => array_values(array_filter(is_array($filter) ? $filter : array($filter),fn(mixed $index) : bool => $index instanceof Filter)),$filters);
			$name = key($function);
			$attributes = array_merge($function[$name]['attributes'],$additional);
			$function[$name]['attributes'] = empty($attributes) ? array($attributes) : $attributes;
			$this->handlers = array_merge($this->handlers,$function);
		endif;
	}
	public function removeEventHandler(object | callable $callback,? string $unique = null) : void {
		if(is_object($callback) and is_a($callback,'Closure') === false):
			$functions = Filter::getFunctions($callback,$unique);
			$keys = array_keys($functions);
			foreach($keys as $name):
				unset($this->handlers[$name]);
			endforeach;
		else:
			$function = Filter::getFunction($callback,$unique);
			$name = array_key_first($function);
			unset($this->handlers[$name]);
		endif;
	}
	public function fetchOneUpdate(array $updates,? callable $callback = null,float $timeout = 0) : object {
		$unique = uniqid('LiveProto');
		$deferredFuture = new DeferredFuture();
		$checker = function(object $update) use($deferredFuture,$callback,&$checker,$unique) : void {
			static $mutex = new LocalMutex;
			$lock = $mutex->acquire();
			try {
				if($deferredFuture->isComplete() === false):
					if(is_null($callback) or async($callback,$update)->await()):
						$deferredFuture->complete($update);
						$this->removeEventHandler($checker,$unique);
					endif;
				endif;
			} catch(\Throwable $error){
				Logging::log('Updates',$error->getMessage(),E_WARNING);
			} finally {
				$lock->release();
			}
		};
		$this->addEventHandler($checker,$unique,array(new Filter\Update(...$updates)));
		$deferredCancellation = new DeferredCancellation();
		return new class($updates,$checker,$deferredFuture,$deferredCancellation,$timeout) extends Instance {
			public function __construct(array $updates,protected Closure $checker,public object $deferredFuture,public object $deferredCancellation,protected float $timeout){
				parent::__construct(['updates'=>$updates,'timeout'=>$timeout]);
			}
			public function await() : mixed {
				$future = $this->deferredFuture->getFuture();
				$cancellation = $this->deferredCancellation->getCancellation();
				if($this->timeout > 0):
					EventLoop::unreference(EventLoop::delay($this->timeout,fn(string $id) : null => $this->cancel(new TimeoutException('Operation timed out'))));
				endif;
				$update = $future->await($cancellation);
				return $update;
			}
			public function cancel(? Throwable $previous = null) : void {
				$this->deferredCancellation->cancel($previous);
			}
		};
	}
	public function applyUpdate(object $update,bool $recovery = false) : void {
		if(isset($update->seq)):
			Logging::log('Updates','Checking SEQ of an update : '.$update->getClass());
			$state = $this->state();
			$update->seq_start ??= $update->seq;
			if($update->seq_start === 0):
				Logging::log('Update Special Case','seq_start = 0');
				array_map($this->applyUpdate(...),$update->updates);
			elseif($state->seq + 1 === $update->seq_start):
				Logging::log('Update Accepted','local seq = '.$state->seq.' (+) 1 (===) seq_start = '.$update->seq_start);
				array_map($this->applyUpdate(...),$update->updates);
			elseif($state->seq + 1 > $update->seq_start):
				Logging::log('Update Skipped','local seq = '.$state->seq.' (+) 1 (>) seq_start = '.$update->seq_start);
				return;
			elseif($state->seq + 1 < $update->seq_start):
				Logging::log('Update Missed','local seq = '.$state->seq.' (+) 1 (<) seq_start = '.$update->seq_start);
				($recovery and isset($this->lock) === false) ? $this->recoveringGaps() : delay(0.5);
				$this->applyUpdate($update,true);
				return;
			endif;
			$state->date = $update->date;
			$state->seq = max($update->seq,$state->seq);
			return;
		endif;
		if(isset($update->qts)):
			Logging::log('Updates','Checking QTS of an update : '.$update->getClass());
			$state = $this->state();
			$update->qts_count = 1;
			if($state->qts <= 0):
				$state->qts = $update->qts - $update->qts_count;
				Logging::log('Updates','Initializing qts = '.$state->qts);
			endif;
			if($update->qts === 0):
				Logging::log('Update Special Case','qts = 0');
			elseif($state->qts + $update->qts_count === $update->qts):
				Logging::log('Update Accepted','local qts = '.$state->qts.' (+) qts count = '.$update->qts_count.' (===) qts = '.$update->qts);
				$state->qts = $update->qts;
			elseif($state->qts + $update->qts_count > $update->qts):
				Logging::log('Update Skipped','local qts = '.$state->qts.' (+) qts count = '.$update->qts_count.' (>) qts = '.$update->qts);
				return;
			elseif($state->qts + $update->qts_count < $update->qts):
				Logging::log('Update Missed','local qts = '.$state->qts.' (+) qts count = '.$update->qts_count.' (<) qts = '.$update->qts);
				($recovery and isset($this->lock) === false) ? $this->recoveringGaps() : delay(0.5);
				$this->applyUpdate($update,true);
				return;
			endif;
		endif;
		if(isset($update->pts,$update->pts_count)):
			Logging::log('Updates','Checking PTS of an update : '.$update->getClass());
			$channel_id = isset($update->channel_id) ? $update->channel_id : (isset($update->message->peer_id->channel_id) ? $update->message->peer_id->channel_id : 0);
			if($channel_id === 0):
				$state = $this->state();
				if($state->pts + $update->pts_count === $update->pts):
					Logging::log('Update Accepted','local pts = '.$state->pts.' (+) pts count = '.$update->pts_count.' (===) pts = '.$update->pts);
					$state->pts = $update->pts;
				elseif($state->pts + $update->pts_count > $update->pts):
					Logging::log('Update Skipped','local pts = '.$state->pts.' (+) pts count = '.$update->pts_count.' (>) pts = '.$update->pts);
					return;
				elseif($state->pts + $update->pts_count < $update->pts):
					Logging::log('Update Missed','local pts = '.$state->pts.' (+) pts count = '.$update->pts_count.' (<) pts = '.$update->pts);
					($recovery and isset($this->lock) === false) ? $this->recoveringGaps() : delay(0.5);
					$this->applyUpdate($update,true);
					return;
				endif;
			else:
				$pts = $this->getChannelPts($channel_id);
				if($pts === 1):
					Logging::log('Update Special Case','channel id = '.$channel_id.' & channel pts = '.$pts);
					$this->setChannelPts($channel_id,$update->pts);
				elseif($pts + $update->pts_count === $update->pts):
					Logging::log('Update Accepted','local pts = '.$pts.' (+) pts count = '.$update->pts_count.' (===) pts = '.$update->pts);
					$this->setChannelPts($channel_id,$update->pts);
				elseif($pts + $update->pts_count > $update->pts):
					Logging::log('Update Skipped','local pts = '.$pts.' (+) pts count = '.$update->pts_count.' (>) pts = '.$update->pts);
					return;
				elseif($pts + $update->pts_count < $update->pts):
					Logging::log('Update Missed','local pts = '.$pts.' (+) pts count = '.$update->pts_count.' (<) pts = '.$update->pts);
					($recovery) ? $this->recoveringChannel($channel_id,$pts) : delay(0.5);
					$this->applyUpdate($update,true);
					return;
				endif;
			endif;
		endif;
		Logging::log('Updates','Applying an update : '.$update->getClass());
		try {
			$encryptedMessage = new Filter\Update('updateNewEncryptedMessage');
			if($encryptedMessage->apply($update)):
				$update->decrypted = $this->client->handle_encrypted_update($update);
			endif;
			$encryption = new Filter\Update('updateEncryption');
			if($encryption->apply($update)):
				if($update->chat instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedChat):
					$this->client->finish_secret_chat_creation($update->chat);
				elseif($update->chat instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedChatRequested):
					$this->client->accept_secret_chat($update->chat);
				elseif($update->chat instanceof \Tak\Liveproto\Tl\Types\Other\EncryptedChatDiscarded):
					$this->client->remove_secret($update->chat->id);
				endif;
			endif;
		} catch(\Throwable $error){
			Logging::log('Updates','An encrypted update was skipped : '.$error->getMessage(),E_WARNING);
			return;
		}
		/* TODO : Add separate handlers to manage updates related to phone calls */
		switch($update->getClass()):
			case 'updateConfig':
				if(isset($this->client->config)):
					$this->client->config = $this->client->help->getConfig();
				endif;
				break;
			case 'updateDcOptions':
				if(isset($this->client->config)):
					$this->client->config->dc_options = $update->dc_options;
				endif;
				break;
			case 'updatePtsChanged':
				$this->state(reset : true);
				break;
			case 'updateNewAuthorization':
				$this->state(reset : true);
				break;
			case 'updateChannelTooLong':
				if(is_null($update->pts) === false):
					$this->setChannelPts($update->channel_id,$update->pts);
				endif;
				$this->recoveringChannel(channel_id : $update->channel_id,pts : $update->pts);
				return;
			case 'updateSentPhoneCode':
				$this->client->apply_sent_code($update->sent_code);
				return;
			case 'updateMessageID':
				return;
		endswitch;
		$this->broadcastUpdate($update);
	}
	public function broadcastUpdate(object $update) : void {
		$update->setClient($this->client);
		foreach($this->handlers as $name => $handler):
			Logging::log('Updates','Handler “'.$handler['name'].'” : Starting parameters checks');
			$paramsCustom = array();
			foreach($handler['parameters'] as $parameter):
				$cloned = clone $update;
				$check = $parameter($cloned);
				if($check === false):
					Logging::log('Updates','Handler “'.$handler['name'].'” : Parameter check failed – skipping');
					continue 2;
				endif;
				$paramsCustom []= $cloned->is_custom;
			endforeach;
			Logging::log('Updates','Handler “'.$handler['name'].'” : Parameters checked , Starting attribute checks');
			if(empty($handler['attributes'])):
				$applies = array();
				goto run;
			endif;
			foreach($handler['attributes'] as $i => $attributes):
				Logging::log('Updates','Handler “'.$handler['name'].'”: Applying attributes – '.$i);
				$applies = array_map(fn(object $attribute) : mixed => $attribute->apply(clone $update),$attributes);
				if(empty($applies)):
					Logging::log('Updates','Handler “'.$handler['name'].'” : Events were not generated – '.$i);
					goto run;
				elseif(in_array(false,$applies) === false):
					Logging::log('Updates','Handler “'.$handler['name'].'” : Events generated – '.$i);
					foreach($applies as $i => $event):
						if($event instanceof Instance):
							$event->setClient($this->client);
						endif;
						if($event instanceof Events and isset($event->addBoundMethods)):
							$applies[$i] = $event->addBoundMethods($event);
						endif;
					endforeach;
					Logging::log('Updates','Handler “'.$handler['name'].'” : Added and initialized bound methods on the event – '.$i);
					goto run;
				else:
					Logging::log('Updates','Handler “'.$handler['name'].'” : Some attributes returned false – skipping – '.$i);
					continue;
				endif;
			endforeach;
			continue;
			run:
			if(empty($applies)):
				Logging::log('Updates','Handler “'.$handler['name'].'” : Dispatching original callback');
				$applies = array(clone $update);
			endif;
			Logging::log('Updates','Handler “'.$handler['name'].'” : Calling callback with events');
			$arguments = array_map(fn(mixed $apply,? bool $paramCustom) : mixed => ($paramCustom and ($apply instanceof Instance) === true and ($apply instanceof Events) === false) ? Events::copy($apply)->setClient($this->client) : $apply,$applies,$paramsCustom);
			async($handler['callback'],...$arguments)->catch(fn(\Throwable $error) : bool => error_log($error->getMessage()));
		endforeach;
	}
	public function &state(bool $reset = false) : object {
		if($reset or ($this->load->state instanceof \stdClass and $this->client->isAuthorized())):
			$this->load->state = $this->client->updates->getState();
		endif;
		return $this->load->state;
	}
	public function recoveringGaps() : void {
		$isLocked = isset($this->lock);
		$this->lock = $this->mutex->acquire();
		$unlock = function() : void {
			if(isset($this->lock)):
				$lock = $this->lock;
				unset($this->lock);
				$lock->release();
			endif;
		};
		if($isLocked === false):
			$state = $this->state();
			$differences = $this->client->get_difference(pts : $state->pts,date : $state->date,qts : $state->qts);
			foreach($differences as $difference):
				$newState = $difference->state ?? $difference->intermediate_state;
				if(isset($difference->new_messages)):
					array_map(fn(object $newMessage) : mixed => async($this->applyUpdate(...),new \Tak\Liveproto\Tl\Types\Other\UpdateNewMessage(['message'=>$newMessage])),$difference->new_messages);
				endif;
				if(isset($difference->new_encrypted_messages)):
					$start_qts = $newState->qts - count($difference->new_encrypted_messages);
					$fn = function(object $newEncryptedMessage) use(&$start_qts) : void {
						async($this->applyUpdate(...),new \Tak\Liveproto\Tl\Types\Other\UpdateNewEncryptedMessage(['message'=>$newEncryptedMessage,'qts'=>++$start_qts]));
					};
					array_map($fn,$difference->new_encrypted_messages);
				endif;
				if(isset($difference->other_updates)):
					array_map(fn(object $update) : mixed => async($this->applyUpdate(...),$update),$difference->other_updates);
				endif;
				delay(0.5);
				Logging::log('New State','new pts = '.$newState->pts.' & new qts = '.$newState->qts.' & new date = '.$newState->date.' & new seq = '.$newState->seq);
				$state->pts = $newState->pts;
				$state->qts = $newState->qts;
				$state->date = $newState->date;
				$state->seq = $newState->seq;
				break; // I didn't want to take them all , I'll give it a chance to get the updates itself :) //
			endforeach;
		endif;
		EventLoop::queue($unlock);
	}
	public function getChannelPts(int $channel_id) : int {
		return key_exists($channel_id,$this->channelsPts) ? $this->channelsPts[$channel_id] : 1;
	}
	public function setChannelPts(int $channel_id,int $pts) : void {
		$this->channelsPts[$channel_id] = $pts;
	}
	public function recoveringChannel(int $channel_id,? int $pts = null) : void {
		try {
			$pts ??= $this->getChannelPts($channel_id);
			$differences = $this->client->get_channel_difference(channel : $channel_id,pts : $pts);
			foreach($differences as $difference):
				if(isset($difference->new_messages)):
					array_map(fn(object $newChannelMessage) : mixed => async($this->applyUpdate(...),new \Tak\Liveproto\Tl\Types\Other\UpdateNewChannelMessage(['message'=>$newChannelMessage])),$difference->new_messages);
				endif;
				if(isset($difference->other_updates)):
					array_map(fn(object $update) : mixed => async($this->applyUpdate(...),$update),$difference->other_updates);
				endif;
				delay(0.5);
				$this->setChannelPts($channel_id,$difference->pts);
			endforeach;
		} catch(\Throwable $error){
			$this->setChannelPts($channel_id,1);
			Logging::log('Updates','Channel '.$channel_id.' updates not being retrieved : '.$error->getMessage(),E_WARNING);
		}
	}
	public function processUpdate(object $update) : void {
		switch($update->getClass()):
			# updatesTooLong#e317af7e = Updates; #
			case 'updatesTooLong':
				$this->recoveringGaps();
				break;
			# updateShort#78d4dec1 update:Update date:int = Updates; #
			case 'updateShort':
				$this->applyUpdate($update->update);
				break;
			# updatesCombined#725b04c3 updates:Vector<Update> users:Vector<User> chats:Vector<Chat> date:int seq_start:int seq:int = Updates; #
			# updates#74ae4240 updates:Vector<Update> users:Vector<User> chats:Vector<Chat> date:int seq:int = Updates; #
			case 'updatesCombined':
			case 'updates':
				$this->saveAccessHash($update);
				$this->applyUpdate($update);
				break;
			# updateShortMessage#313bc7f8 flags:# out:flags.1?true mentioned:flags.4?true media_unread:flags.5?true silent:flags.13?true id:int user_id:long message:string pts:int pts_count:int date:int fwd_from:flags.2?MessageFwdHeader via_bot_id:flags.11?long reply_to:flags.3?MessageReplyHeader entities:flags.7?Vector<MessageEntity> ttl_period:flags.25?int = Updates; #
			# updateShortChatMessage#4d6deea5 flags:# out:flags.1?true mentioned:flags.4?true media_unread:flags.5?true silent:flags.13?true id:int from_id:long chat_id:long message:string pts:int pts_count:int date:int fwd_from:flags.2?MessageFwdHeader via_bot_id:flags.11?long reply_to:flags.3?MessageReplyHeader entities:flags.7?Vector<MessageEntity> ttl_period:flags.25?int = Updates; #
			case 'updateShortMessage':
			case 'updateShortChatMessage':
				/*
				 * We need to get users & chats parameters to cache access hashes belonging to peers
				 *
				 * So if you have trouble getting the peers, just enable `autoCachePeers` in the settings
				 */
				try {
					# updateShortMessage#313bc7f8 flags:# out:flags.1?true mentioned:flags.4?true media_unread:flags.5?true silent:flags.13?true id:int user_id:long message:string pts:int pts_count:int date:int fwd_from:flags.2?MessageFwdHeader via_bot_id:flags.11?long reply_to:flags.3?MessageReplyHeader entities:flags.7?Vector<MessageEntity> ttl_period:flags.25?int = Updates; #
					if(isset($update->user_id)):
						$update->peer_id = new \Tak\Liveproto\Tl\Types\Other\PeerUser(['user_id'=>$update->user_id]);
						$update->from_id = new \Tak\Liveproto\Tl\Types\Other\PeerUser(['user_id'=>$update->out ? $this->client->get_peer_id('me') : $update->user_id]);
						if($this->load->peers->getPeer(type : 'users',by : 'id',what : $update->user_id) == false):
							$entity = $this->client->inputPeerUserFromMessage(peer : $this->client->inputPeerSelf(),msg_id : $update->id,user_id : $update->user_id);
							$this->client->users->getFullUser($entity);
						endif;
					# updateShortChatMessage#4d6deea5 flags:# out:flags.1?true mentioned:flags.4?true media_unread:flags.5?true silent:flags.13?true id:int from_id:long chat_id:long message:string pts:int pts_count:int date:int fwd_from:flags.2?MessageFwdHeader via_bot_id:flags.11?long reply_to:flags.3?MessageReplyHeader entities:flags.7?Vector<MessageEntity> ttl_period:flags.25?int = Updates; #
					elseif(isset($update->chat_id,$update->from_id)):
						$update->peer_id = new \Tak\Liveproto\Tl\Types\Other\PeerChat(['chat_id'=>$update->chat_id]);
						$update->from_id = new \Tak\Liveproto\Tl\Types\Other\PeerUser(['user_id'=>$update->from_id]);
						if($this->load->peers->getPeer(type : 'users',by : 'id',what : $update->from_id) == false):
							$entity = $this->client->inputPeerUserFromMessage(peer : $this->client->inputPeerChat(chat_id : $update->chat_id),msg_id : $update->id,user_id : $update->from_id);
							$this->client->users->getFullUser($entity);
						endif;
					endif;
					$message = $update->clone(new \Tak\Liveproto\Tl\Types\Other\Message,true);
					$newMessage = new \Tak\Liveproto\Tl\Types\Other\UpdateNewMessage(['message'=>$message,'pts'=>$update->pts,'pts_count'=>$update->pts_count]);
					$this->applyUpdate($newMessage);
					break;
				} catch(\Throwable $error){
					Logging::log('Short Message','When completing the update : '.$error->getMessage(),E_WARNING);
				}
			# updateShortSentMessage#9015e101 flags:# out:flags.1?true id:int pts:int pts_count:int date:int media:flags.9?MessageMedia entities:flags.7?Vector<MessageEntity> ttl_period:flags.25?int = Updates; #
			case 'updateShortSentMessage':
				Logging::log('Process Updates','Received '.$update->getClass(),E_NOTICE);
				$local_pts = intval($update->pts - $update->pts_count);
				$difference = $this->client->updates->getDifference(pts : $local_pts,date : $update->date,pts_limit : $update->pts_count,qts : 0);
				if(isset($difference->new_messages) and count($difference->new_messages) === $update->pts_count):
					Logging::log('Process Updates','updates.getDifference results accepted for '.$update->getClass());
					$fn = function(object $newMessage) use(&$local_pts) : void {
						$this->applyUpdate(new \Tak\Liveproto\Tl\Types\Other\UpdateNewMessage(['message'=>$newMessage,'pts'=>++$local_pts,'pts_count'=>1]));
					};
					array_map($fn,$difference->new_messages);
				endif;
				break;
			default:
				Logging::log('Process Updates','Unknown update : '.$update->getClass(),E_ERROR);
		endswitch;
	}
	# https://core.telegram.org/api/peers #
	# https://core.telegram.org/api/min #
	public function saveAccessHash(object $update) : void {
		$chats = array_filter($update->chats,fn(mixed $peer) : bool => isset($peer->min) === false || $peer->min === false);
		$this->load->peers->setPeers(type : 'chats',peers : array_map(fn(mixed $peer) : array => array('id'=>$peer->id,'access_hash'=>intval($peer->access_hash)),$chats));
		$users = array_filter($update->users,fn(mixed $peer) : bool => isset($peer->min) === false || $peer->min === false);
		$this->load->peers->setPeers(type : 'users',peers : array_map(fn(mixed $peer) : array => array('id'=>$peer->id,'access_hash'=>intval($peer->access_hash)),$users));
	}
	public function __debugInfo() : array {
		return array(
			'handlers'=>array_map(static fn(array $handler) : array => array_diff_key($handler,['callback'=>null]),$this->handlers)
		);
	}
}

?>