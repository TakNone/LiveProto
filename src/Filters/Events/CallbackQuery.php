<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class CallbackQuery extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | bool {
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateInlineBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBusinessBotCallbackQuery):
			$applies = array_map(fn($filter) : mixed => $filter->apply($update),$this->items);
			$event = Events::copy($update);
			$event->addBoundMethods = $this->boundMethods(...);
			return in_array(false,$applies) ? false : $event;
		else:
			return false;
		endif;
	}
	private function boundMethods(object $event) : object {
		$event->getPeer = function(mixed $peer = null) use($event) : object {
			return $event->get_input_peer(is_null($peer) ? ($event->class === 'updateBotCallbackQuery' ? $event->peer : ($event->class === 'updateBusinessBotCallbackQuery' ? $event->message->peer : $event->user_id)) : $peer);
		};
		$event->getPeerId = function() use($event) : int {
			$peer = ($event->class === 'updateBotCallbackQuery' ? $event->peer : ($event->class === 'updateBusinessBotCallbackQuery' ? $event->message->peer : $event));
			try {
				return $event->get_peer_id($peer);
			} catch(\Throwable){
				throw new \Exception('The update does not contain a valid peer id');
			}
		};
		$event->respond = function(mixed ...$args) use($event) : object {
			$args += ['businessConnectionId'=>isset($event->connection_id) ? $event->connection_id : null];
			$peer = $event->getPeer();
			return $event->send_content($peer,...$args);
		};
		$event->reply = function(mixed ...$args) use($event) : object {
			if($event->class === 'updateBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
				$reply_to = array_key_exists('input_reply_to',$args) ? $args['input_reply_to'] : [];
				$args += ['reply_to'=>$event->inputReplyToMessage(isset($event->message) ? $event->message->id : $event->msg_id,...$reply_to),'businessConnectionId'=>isset($event->connection_id) ? $event->connection_id : null];
				$peer = $event->getPeer();
				return $event->send_content($peer,...$args);
			elseif($event->class === 'updateInlineBotCallbackQuery'):
				throw new \Exception('This method is not available for this update');
			endif;
		};
		$event->forward = function(mixed $peer,mixed ...$args) use($event) : object {
			$reply_to = array_key_exists('input_reply_to',$args) ? $args['input_reply_to'] : [];
			$args += empty($reply_to) ? [] : ['reply_to'=>(isset($reply_to['peer']) || isset($reply_to['story_id'])) ? $event->inputReplyToStory(...$reply_to) : $event->inputReplyToMessage(...$reply_to)];
			$to = $event->get_input_peer($peer);
			$peer = $event->getPeer();
			if($event->class === 'updateBotCallbackQuery'):
				return $event->getClient()->messages->forwardMessages($peer,array($event->msg_id),array(random_int(PHP_INT_MIN,PHP_INT_MAX)),$to,...$args);
			elseif($event->class === 'updateInlineBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
				throw new \Exception('This method is not available for this update');
			endif;
		};
		$event->edit = function(? string $message = null,? object $media = null,mixed ...$args) use($event) : mixed {
			$peer = $event->getPeer();
			$args += ['message'=>$message,'media'=>$media];
			if($event->class === 'updateBotCallbackQuery'or $event->class === 'updateBusinessBotCallbackQuery'):
				$args += ['businessConnectionId'=>isset($event->connection_id) ? $event->connection_id : null];
				return $event->getClient()->messages->editMessage($peer,($event->class === 'updateBotCallbackQuery' ? $event->msg_id : $event->message->id),...$args);
			elseif($event->class === 'updateInlineBotCallbackQuery'):
				return $event->getClient()->messages->editInlineBotMessage($event->msg_id,...$args);
			endif;
		};
		$event->pin = function(mixed ...$args) use($event) : object {
			$peer = $event->getPeer();
			if($event->class === 'updateBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
				$args += ['unpin'=>null,'businessConnectionId'=>isset($event->connection_id) ? $event->connection_id : null];
				return $event->getClient()->messages->updatePinnedMessage($peer,($event->class === 'updateBotCallbackQuery' ? $event->msg_id : $event->message->id),...$args);
			elseif($event->class === 'updateInlineBotCallbackQuery'):
				throw new \Exception('This method is not available for this update');
			endif;
		};
		$event->unpin = function(bool $all = false,mixed ...$args) use($event) : object {
			$peer = $event->getPeer();
			if($event->class === 'updateBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
				if($all === true):
					return $event->getClient()->messages->unpinAllMessages($peer,...$args);
				else:
					$args += ['unpin'=>true,'businessConnectionId'=>isset($event->connection_id) ? $event->connection_id : null];
					return $event->getClient()->messages->updatePinnedMessage($peer,($event->class === 'updateBotCallbackQuery' ? $event->msg_id : $event->message->id),...$args);
				endif;
			elseif($event->class === 'updateInlineBotCallbackQuery'):
				throw new \Exception('This method is not available for this update');
			endif;
		};
		$event->delete = function(? true $revoke = null) use($event) : object {
			if($event->class === 'updateBotCallbackQuery'):
				return $event->peer instanceof \Tak\Liveproto\Tl\Types\Other\PeerUser ? $event->getClient()->messages->deleteMessages(array($event->msg_id),$revoke,businessConnectionId : isset($event->connection_id) ? $event->connection_id : null) : $event->getClient()->channels->deleteMessages($event->getPeer(),array($event->msg_id));
			elseif($event->class === 'updateInlineBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
				throw new \Exception('This method is not available for this update');
			endif;
		};
		$event->reaction = function(string | int | array | null $reaction,mixed ...$args) use($event) : object {
			$peer = $event->getPeer();
			if($event->class === 'updateBotCallbackQuery'):
				if(is_null($reaction)):
					$reaction = $event->reactionEmpty();
				elseif(is_string($reaction)):
					$reaction = array($event->reactionEmoji($reaction));
				elseif(is_int($reaction)):
					$reaction = array($event->reactionCustomEmoji($reaction));
				elseif(is_array($reaction)):
					$reaction = array_map(fn(string | int | null $emoji) : object => is_string($emoji) ? $event->reactionEmoji($emoji) : (is_int($emoji) ? $event->reactionCustomEmoji($emoji) : $event->reactionEmpty()),$reaction);
				endif;
				$args += ['reaction'=>$reaction];
				return $event->getClient()->messages->sendReaction($peer,$event->id,...$args);
			elseif($event->class === 'updateInlineBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
				throw new \Exception('This method is not available for this update');
			endif;
		};
		$event->paidReaction = function(int $count = 0x1,mixed ...$args) use($event) : bool {
			$peer = $event->getPeer();
			$random_id = intval(time() << 32) | random_int(0x0,0xffffffff);
			return $event->getClient()-messages->sendPaidReaction($peer,$event->id,$count,$random_id,...$args);
		};
		$event->answerCallback = function(int $cache,mixed ...$args) use($event) : bool {
			return $event->getClient()->messages->setBotCallbackAnswer($event->query_id,$cache,...$args);
		};
		if($event->class === 'updateBotCallbackQuery' or $event->class === 'updateBusinessBotCallbackQuery'):
			$event->type = $event->get_peer_type($event->class === 'updateBotCallbackQuery' ? $event->peer : $event->message->peer)->getChatType();
		endif;
		unset($event->addBoundMethods);
		return $event;
	}
}

?>