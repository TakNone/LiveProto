<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class NewJoinRequest extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | bool {
		# updatePendingJoinRequests#7063c3db peer:Peer requests_pending:int recent_requesters:Vector<long> = Update; #
		# updateBotChatInviteRequester#11dfa986 peer:Peer date:int user_id:long about:string invite:ExportedChatInvite qts:int = Update; #
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdatePendingJoinRequests or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotChatInviteRequester):
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
			return $event->get_input_peer(is_null($peer) ? $event->peer : $peer);
		};
		$event->getPeerId = function() use($event) : int {
			try {
				return $event->get_peer_id($event->peer);
			} catch(\Throwable){
				throw new \Exception('The update does not contain a valid peer id');
			}
		};
		$event->respond = function(mixed ...$args) use($event) : object {
			$peer = $event->getPeer();
			return $event->send_content($peer,...$args);
		};
		$event->hideRequest = function(? true $approved = null,bool $all = false,...$args) use($event) : object {
			$peer = $event->getPeer();
			if($all === true):
				return $event->getClient()->messages->hideAllChatJoinRequests($peer,...$args);
			else:
				$user = $event->get_input_user($event->class === 'updateBotChatInviteRequester' ? $event->user_id : current($event->recent_requesters));
				return $event->getClient()->messages->hideChatJoinRequest($peer,$user,$approved);
			endif;
		};
		$event->type = $event->get_peer_type($event->peer)->getChatType();
		unset($event->addBoundMethods);
		return $event;
	}
}

?>