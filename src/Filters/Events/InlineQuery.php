<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters\Events;

use Tak\Liveproto\Filters\Filter;

use Tak\Liveproto\Handlers\Events;

final class InlineQuery extends Filter {
	public function __construct(Filter ...$filters){
		$this->items = $filters;
	}
	public function apply(object $update) : object | bool {
		if($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
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
		$event->answerInline = function(array $results,int $cache,? string $switch_text = null,? string $switch_url = null,? string $start_param = null,mixed ...$args) use($event) : bool {
			if(is_null($switch_text) === false or is_null($switch_url) === false or is_null($start_param) === false):
				if(is_null($switch_url) === false):
					$args += ['switch_webview'=>$event->inlineBotWebView(text : strval($switch_text ?? $switch_url),url : strval($switch_url))];
				else:
					$args += ['switch_pm'=>$event->inlineBotSwitchPM(text : strval($switch_text ?? $start_param),start_param : substr(trim(preg_replace('/[^\w-]+/',strval(null),strval($start_param ?? $switch_text))),0,64))];
				endif;
			endif;
			return $event->getClient()->messages->setInlineBotResults($event->query_id,$results,$cache,...$args);
		};
		$event->type = match(true){
			$event->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeBroadcast => 'channel',
			$event->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeMegagroup => 'supergroup',
			$event->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypePM => 'private',
			$event->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeChat => 'group',
			$event->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeSameBotPM => 'self',
			$event->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeBotPM => 'bot',
			default => 'unknown'
		};
		unset($event->addBoundMethods);
		return $event;
	}
}

?>