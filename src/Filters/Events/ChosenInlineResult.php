<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class ChosenInlineResult extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | bool {
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineSend):
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
			return $event->get_input_peer(is_null($peer) ? $event->user_id : $peer);
		};
		$event->getPeerId = function() use($event) : int {
			try {
				return $event->get_peer_id($event);
			} catch(\Throwable){
				throw new \Exception('The update does not contain a valid peer id');
			}
		};
		$event->respond = function(mixed ...$args) use($event) : object {
			$peer = $event->getPeer();
			return $event->send_content($peer,...$args);
		};
		$event->edit = function(mixed ...$args) use($event) : bool {
			if($event->msg_id === null):
				throw new \Exception('The inline result does not contain a msg id');
			else:
				return $event->getClient()->messages->editInlineBotMessage($event->msg_id,...$args);
			endif;
		};
		unset($event->addBoundMethods);
		return $event;
	}
}

?>