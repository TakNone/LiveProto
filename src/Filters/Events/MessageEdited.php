<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class MessageEdited extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | false {
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateEditMessage or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateEditChannelMessage or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotEditBusinessMessage):
			$messages = new Messages(...$this->items);
			return $messages->apply($update);
		else:
			return false;
		endif;
	}
}

?>