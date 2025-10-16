<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Tl\Pagination;

use Tak\Liveproto\Enums\FileType;

use Tak\Liveproto\Attributes\Type;

use Tak\Attributes\Common\Vector;

use Tak\Attributes\Common\Is;

use Iterator;

use Closure;

trait Messages {
	public function send_content(
		string | int | object $peer,
		? string $message = null,
		? string $parse_mode = null,
		string | object | null $media = null,
		string | int | null | object $send_as = null,
		FileType $file_type = FileType::DOCUMENT,
		array $uploaded = array(),
		mixed ...$args
	) : object | array {
		$inputPeer = $this->get_input_peer($peer);
		$inputSendAs = $this->get_input_peer($send_as);
		$messages = str_split(strval($message),1 << ($media ? 10 : 12)) ?: array(null);
		if(is_string($media)):
			$inputMedia = $this->get_input_media_uploaded($media,$file_type,...$uploaded);
		elseif(is_object($media)):
			$inputMedia = $this->get_input_media($media);
		else:
			$inputMedia = null;
		endif;
		$parser = match(strtolower(strval($parse_mode))){
			'html' , 'htm' => $this->html(...),
			'markdown' , 'markdownv2' , 'md' => $this->markdown(...),
			default => null
		};
		$sents = array();
		foreach($messages as $text):
			if(is_null($parser) === false):
				list($text,$entities) = $parser(strval($text));
				$args['entities'] = $entities;
			endif;
			if(is_null($inputMedia)):
				$sents []= $this->messages->sendMessage(...$args,peer : $inputPeer,message : strval($text),random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),send_as : ($send_as ? $inputSendAs : null));
			else:
				$sents []= $this->messages->sendMedia(...$args,peer : $inputPeer,media : $inputMedia,message : strval($text),random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),send_as : ($send_as ? $inputSendAs : null));
			endif;
		endforeach;
		return count($sents) > 1 ? $sents : reset($sents);
	}
	protected function fetch_messages(
		string | int | null | object $peer = null,
		string | int | null | object $offset_peer = null,
		int $offset = 0,
		int $offset_id = 0,
		int $offset_date = 0,
		int $limit = 100,
		int $min_id = 0,
		int $max_id = 0,
		int $min_date = 0,
		int $max_date = 0,
		bool $unread_mentions = false,
		bool $unread_reactions = false,
		bool $recent_locations = false,
		bool $posts = false,
		bool $search = false,
		bool $saved = false,
		bool $scheduled = false,
		#[Vector(new Is('int'))] ? array $id = null,
		#[Type('MessagesFilter')] ? object $filter = null,
		? string $query = null,
		int | bool | null $reply_to = null,
		? int $shortcut_id = null,
		Closure | array | null $hashgen = null,
		mixed ...$args
	) : Iterator {
		$inputPeer = $this->get_input_peer($peer);
		$inputOffsetPeer = $this->get_input_peer($offset_peer);
		if($unread_mentions):
			$fetchResults = fn(int $offset,int $limit) : array => $this->messages->getUnreadMentions(...$args,peer : $inputPeer,offset_id : $offset_id,add_offset : $offset,limit : $limit,max_id : $max_id,min_id : $min_id)->messages;
		elseif($unread_reactions):
			$fetchResults = fn(int $offset,int $limit) : array => $this->messages->getUnreadReactions(...$args,peer : $inputPeer,offset_id : $offset_id,add_offset : $offset,limit : $limit,max_id : $max_id,min_id : $min_id)->messages;
		elseif($recent_locations):
			$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->getRecentLocations(...$args,peer : $inputPeer,limit : $limit,hash : $hash)->messages;
			$hashgen = array('id','edit_date');
		elseif($posts):
			$fetchResults = fn(int $offset,int $limit) : array => $this->channels->searchPosts(...$args,query : $query,offset_rate : $offset,offset_peer : $inputOffsetPeer,offset_id : $offset_id,limit : $limit)->messages;
		elseif($search):
			if(is_null($peer)):
				$fetchResults = fn(int $offset,int $limit) : array => $this->messages->searchGlobal(...$args,q : strval($query),filter : is_null($filter) ? $this->inputMessagesFilterEmpty() : $filter,min_date : $min_date,max_date : $max_date,offset_rate : $offset,offset_peer : $inputOffsetPeer,offset_id : $offset_id,limit : $limit)->messages;
			else:
				$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->search(...$args,peer : $inputPeer,q : strval($query),filter : is_null($filter) ? $this->inputMessagesFilterEmpty() : $filter,min_date : $min_date,max_date : $max_date,offset_id : $offset_id,add_offset : $offset,limit : $limit,max_id : $max_id,min_id : $min_id,hash : $hash)->messages;
			endif;
		elseif($scheduled):
			if(is_null($id)):
				$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->getScheduledHistory(...$args,peer : $inputPeer,hash : $hash)->messages;
				$hashgen = array('id','edit_date','date');
			else:
				$fetchResults = fn(int $offset,int $limit) : array => ($slice = array_slice($id,$offset,$limit)) ? $this->messages->getScheduledMessages(...$args,peer : $inputPeer,id : $slice)->messages : null;
			endif;
		elseif(is_int($reply_to)):
			$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->getReplies(...$args,peer : $inputPeer,msg_id : $reply_to,offset_id : $offset_id,offset_date : $offset_date,add_offset : $offset,limit : $limit,max_id : $max_id,min_id : $min_id,hash : $hash)->messages;
		elseif(is_null($shortcut_id) === false):
			$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->getQuickReplyMessages(...$args,shortcut_id : $shortcut_id,id : $id,hash : $hash)->messages;
			$hashgen = array('id','edit_date');
		elseif(is_null($id) === false):
			$id = array_map(fn(int $value) : object => $reply_to ? $this->inputMessageReplyTo(id : $value) : $this->inputMessageID(id : $value),$id);
			if(is_null($peer)):
				$fetchResults = fn(int $offset,int $limit) : array => ($slice = array_slice($id,$offset,$limit)) ? $this->messages->getMessages(...$args,id : $slice)->messages : null;
			else:
				$inputChannel = $this->get_input_channel($peer);
				$fetchResults = fn(int $offset,int $limit) : array => ($slice = array_slice($id,$offset,$limit)) ? $this->channels->getMessages(...$args,channel : $inputChannel,id : $slice)->messages : null;
			endif;
		elseif(is_null($peer) === false):
			if($saved):
				$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->getSavedHistory(...$args,peer : $inputPeer,offset_id : $offset_id,offset_date : $offset_date,add_offset : $offset,limit : $limit,max_id : $max_id,min_id : $min_id,hash : $hash)->messages;
			else:
				$fetchResults = fn(int $offset,int $limit,int $hash) : array => $this->messages->getHistory(...$args,peer : $inputPeer,offset_id : $offset_id,offset_date : $offset_date,add_offset : $offset,limit : $limit,max_id : $max_id,min_id : $min_id,hash : $hash)->messages;
			endif;
		else:
			$messages = $this->messages->searchSentMedia(...$args,q : strval($query),filter : is_null($filter) ? $this->inputMessagesFilterEmpty() : $filter,limit : 0x7fffffff)->messages;
			$fetchResults = fn(int $offset,int $limit) : array => array_slice($messages,$offset,$limit);
		endif;
		return new Pagination($fetchResults,$offset,$limit,$hashgen);
	}
}

?>