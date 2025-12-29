<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class Precheckout extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | bool {
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotPrecheckoutQuery):
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
			return $event->user_id;
		};
		$event->respond = function(mixed ...$args) use($event) : object {
			$args += ['businessConnectionId'=>isset($event->connection_id) ? $event->connection_id : null];
			$peer = $event->getPeer();
			return $event->send_content($peer,...$args);
		};
		$event->approve = function(...$args) use($event) : object {
			return $event->getClient()->messages->setBotPrecheckoutResults($event->query_id,...$args);
		};
		unset($event->addBoundMethods);
		return $event;
	}
}

?>