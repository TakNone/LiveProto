<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class NewMessage extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | false {
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateNewMessage or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateNewChannelMessage or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotNewBusinessMessage or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateQuickReplyMessage):
			$messages = new Messages(...$this->items);
			return $messages->apply($update);
		else:
			return false;
		endif;
	}
}

?>