<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Attributes\Type;

trait Inline {
	protected function edit_inline(
		#[Type('InputBotInlineMessageID')] object $id,
		? string $message = null,
		? string $parse_mode = null,
		string | object | null $media = null,
		array | null | object $reply_markup = null,
		FileType $file_type = FileType::DOCUMENT,
		array $uploaded = array(),
		mixed ...$args
	) : bool {
		if(is_string($media)):
			$inputMedia = $this->get_input_media_uploaded($media,$file_type,...$uploaded);
		elseif(is_object($media)):
			$inputMedia = $this->get_input_media($media);
		else:
			$inputMedia = null;
		endif;
		$inputReplyMarkup = is_array($reply_markup) ? $this->create_reply_markup($reply_markup) : $reply_markup;
		$parser = match(strtolower(strval($parse_mode))){
			'html' , 'htm' => $this->html(...),
			'markdown' , 'markdownv2' , 'md' => $this->markdown(...),
			default => null
		};
		if(is_null($parser) === false and is_null($message) === false):
			list($message,$entities) = $parser($message);
			$args['entities'] = $entities;
		endif;
		return $this->messages->editInlineBotMessage(...$args,id : $id,message : $message,media : $inputMedia,reply_markup : $inputReplyMarkup);
	}
	public function inline_query(
		string | int | object $bot,
		? string $query = null,
		? string $offset = null,
		string | int | null | object $peer = null,
		? object $geo_point = null
	) : mixed {
		$results = $this->messages->getInlineBotResults(bot : $this->get_input_peer($bot),peer : $this->get_input_peer($peer),query : strval($query),offset : strval($offset),geo_point : $geo_point);
		$results->click = function(? int $index = null,? string $id = null,? string $type = null,mixed ...$args) use($results,$peer) : mixed {
			if(is_null($id) === false):
				$array = array_filter($results->results,fn(object $result) : bool => $result->id === $id);
				$result = reset($array);
			elseif(is_null($type) === false):
				$array = array_values(array_filter($results->results,fn(object $result) : bool => $result->type === $type));
				$result = is_null($index) ? reset($array) : $array[$index];
			elseif(is_null($index) === false):
				$result = $results->results[$index];
			else:
				throw new \InvalidArgumentException('One of the index / id / type parameters must be entered in the inline query function !');
			endif;
			if(is_object($result)):
				return $this->click_inline($results->query_id,$result->id,$peer,...$args);
			else:
				throw new \Exception('Your desired result was not found !');
			endif;
		};
		return $results;
	}
	public function get_prepared_inline_message(string | int | object $bot,string $id) : mixed {
		$result = $this->messages->getPreparedInlineMessage(bot : $this->get_input_peer($bot),id : $id);
		$result->click = function(string | int | object $peer,mixed ...$args) use($result) : mixed {
			return $this->click_inline($result->query_id,$result->result->id,$peer,...$args);
		};
		return $result;
	}
	public function click_inline(
		int $query_id,
		string $id,
		string | int | null | object $peer = null,
		mixed ...$args
	) : mixed {
		$result = $this->messages->sendInlineBotResult($this->get_input_peer($peer),random_int(PHP_INT_MIN,PHP_INT_MAX),$query_id,$id,...$args);
		if(is_array($result->updates)):
			$array = array_filter($result->updates,fn(object $update) : bool => $update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message);
			$update = reset($array);
			if(is_object($update) and is_object($update->message->reply_markup)):
				$result->click = fn(mixed ...$arguments) : mixed => $this->click_button($update->message,...$arguments);
			endif;
		endif;
		return $result;
	}
}

?>