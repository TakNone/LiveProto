<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Filters\Filter;

final class Regex extends Filter {
	public function __construct(string ...$patterns){
		$this->items = $patterns;
	}
	public function apply(object $update) : array | false {
		$update->regex = new \stdClass;
		$update->regex->patterns = $this->items;
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineSend):
			if($update->regex->matched = $this->match($update->query)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
			if($update->regex->matched = $this->match($update->query)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
			if($update->data and $update->regex->matched = $this->match($update->data)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateInlineBotCallbackQuery):
			if($update->data and $update->regex->matched = $this->match($update->data)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBusinessBotCallbackQuery):
			if($update->data and $update->regex->matched = $this->match($update->data)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateDraftMessage):
			if($update->draft->message and $update->regex->matched = $this->match($update->draft->message)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateStory):
			if($update->story->caption and $update->regex->matched = $this->match($update->story->caption)):
				return $update->regex->matched;
			endif;
		elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotPrecheckoutQuery):
			if($update->regex->matched = $this->match($update->payload)):
				return $update->regex->matched;
			endif;
		elseif(isset($update->message) and $update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
			if($update->regex->matched = $this->match($update->message->message)):
				return $update->regex->matched;
			endif;
		endif;
		return false;
	}
	private function match(string $subject) : array {
		$matched = array();
		foreach($this->items as $pattern):
			if(preg_match($pattern,$subject,$match)):
				$matched = array_merge($matched,$match);
			endif;
		endforeach;
		return $matched;
	}
}

?>