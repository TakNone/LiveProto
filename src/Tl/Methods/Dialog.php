<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Tl\Pagination;

use Tak\Liveproto\Attributes\Type;

use Iterator;

use Closure;

trait Dialog {
	protected function parse_dialogs(#[Type(['messages.Dialogs','messages.SavedDialogs','messages.peerDialogs'])] object $results) : array {
		$dialogs = array();
		foreach($results->dialogs as $dialog):
			$message = array_filter($results->messages,fn(object $message) => $message->id === $dialog->top_message);
			$dialogs []= (object) ['dialog'=>$dialog,'message'=>reset($message)];
		endforeach;
		return $dialogs;
	}
	public function get_dialogs(
		string | int | null | object $offset_peer = null,
		int $offset = 0,
		int $offset_id = 0,
		int $offset_date = 0,
		int $limit = 100,
		bool $saved = false,
		bool $pinned = false,
		Closure | array | null $hashgen = null,
		mixed ...$args
	) : Iterator {
		if($saved):
			if($pinned):
				$fetchResults = function(int $offset,int $limit) use($args) : array {
					Logging::log('Pinned Saved Dialog','offset = '.$offset.' & limit = '.$limit);
					$results = $this->messages->getPinnedSavedDialogs(...$args);
					switch($results->getClass()):
						# messages.savedDialogs#f83ae221 dialogs:Vector<SavedDialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> = messages.SavedDialogs; #
						# messages.savedDialogsSlice#44ba9dd9 count:int dialogs:Vector<SavedDialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> = messages.SavedDialogs; #
						case 'messages.savedDialogs':
						case 'messages.savedDialogsSlice':
							return array_slice($this->parse_dialogs($results),$offset,$limit);
						# messages.savedDialogsNotModified#c01f6fe8 count:int = messages.SavedDialogs; #
						case 'messages.savedDialogsNotModified':
							return array();
					endswitch;
				};
			else:
				$fetchResults = function(int $offset,int $limit,int $hash) use(&$offset_peer,&$offset_id,&$offset_date,$args) : array {
					Logging::log('Saved Dialog','offset id = '.$offset_id.' & offset date = '.$offset_date.' & limit = '.$limit);
					$inputOffsetPeer = $this->get_input_peer($offset_peer);
					$results = $this->messages->getSavedDialogs(...$args,offset_peer : $inputOffsetPeer,offset_id : $offset_id,offset_date : $offset_date,limit : $limit,hash : $hash);
					switch($results->getClass()):
						# messages.savedDialogs#f83ae221 dialogs:Vector<SavedDialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> = messages.SavedDialogs; #
						# messages.savedDialogsSlice#44ba9dd9 count:int dialogs:Vector<SavedDialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> = messages.SavedDialogs; #
						case 'messages.savedDialogs':
						case 'messages.savedDialogsSlice':
							foreach(array_reverse($results->messages) as $last):
								if($last->getClass() !== 'messageEmpty'):
									list($offset_peer,$offset_id,$offset_date) = array($last->peer_id,$last->id - 1,$last->date);
									break;
								endif;
							endforeach;
							return $this->parse_dialogs($results);
						# messages.savedDialogsNotModified#c01f6fe8 count:int = messages.SavedDialogs; #
						case 'messages.savedDialogsNotModified':
							return array();
					endswitch;
				};
				/*
				 * TODO :
				 * Unfortunately, generating a hash to cache the results of this method did not work
				 * https://github.com/DrKLO/Telegram/blob/ddc90f16be1ab952114005347e0102365ba6460b/TMessagesProj/src/main/java/org/telegram/messenger/SavedMessagesController.java#L253-L257
				 * I followed the same procedure as Telegram Android, but I don't know where is the problem
				$hashgen = function(int $hash,array $results) : int {
					foreach(array_reverse($results) as $result):
						$hash = Helper::hashGeneration($hash,array(intval($result->dialog->pinned),$this->get_peer_id($result->dialog->peer),$result->message->id,$result->message->date));
					endforeach;
					return $hash;
				};
				 */
			endif;
		else:
			if($pinned):
				$fetchResults = function(int $offset,int $limit) use($args) : array {
					Logging::log('Pinned Dialog','offset = '.$offset.' & limit = '.$limit);
					$args += ['folder_id'=>0];
					$results = $this->messages->getPinnedDialogs(...$args);
					switch($results->getClass()):
						# messages.peerDialogs#3371c354 dialogs:Vector<Dialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> state:updates.State = messages.PeerDialogs; #
						case 'messages.peerDialogs':
							return array_slice($this->parse_dialogs($results),$offset,$limit);
						default:
							return array();
					endswitch;
				};
			else:
				$fetchResults = function(int $offset,int $limit,int $hash) use(&$offset_peer,&$offset_id,&$offset_date,$args) : array {
					Logging::log('Dialog','offset id = '.$offset_id.' & offset date = '.$offset_date.' & limit = '.$limit);
					$inputOffsetPeer = $this->get_input_peer($offset_peer);
					$results = $this->messages->getDialogs(...$args,offset_peer : $inputOffsetPeer,offset_id : $offset_id,offset_date : $offset_date,limit : $limit,hash : $hash);
					switch($results->getClass()):
						# messages.dialogs#15ba6c40 dialogs:Vector<Dialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> = messages.Dialogs; #
						# messages.dialogsSlice#71e094f3 count:int dialogs:Vector<Dialog> messages:Vector<Message> chats:Vector<Chat> users:Vector<User> = messages.Dialogs; #
						case 'messages.dialogs':
						case 'messages.dialogsSlice':
							foreach(array_reverse($results->messages) as $last):
								if($last->getClass() !== 'messageEmpty'):
									list($offset_peer,$offset_id,$offset_date) = array($last->peer_id,$last->id,$last->date);
									break;
								endif;
							endforeach;
							return $this->parse_dialogs($results);
						# messages.dialogsNotModified#f0e3e596 count:int = messages.Dialogs; #
						case 'messages.dialogsNotModified':
							return array();
					endswitch;
				};
			endif;
		endif;
		return new Pagination($fetchResults,$offset,$limit,$hashgen);
	}
	public function get_difference(
		int $pts = 1,
		int $date = 1,
		int $qts = 1,
		? int $total_limit = 0x7fffffff,
		? int $pts_limit = null,
		? int $qts_limit = null,
		bool $deep = false
	) : \Generator {
		while(true):
			Logging::log('Difference','pts = '.$pts.' & date = '.$date.' & qts = '.$qts);
			try {
				$difference = $this->updates->getDifference(pts : $pts,date : $date,qts : $qts,pts_total_limit : $total_limit,pts_limit : $pts_limit,qts_limit : $qts_limit,timeout : 3);
			} catch(\Throwable $error){
				$difference = new \Tak\Liveproto\Tl\Types\Updates\DifferenceEmpty;
			}
			if($difference instanceof \Tak\Liveproto\Tl\Types\Updates\Difference):
				$pts = $difference->state->pts;
				$date = $difference->state->date;
				$qts = $difference->state->qts;
			elseif($difference instanceof \Tak\Liveproto\Tl\Types\Updates\DifferenceSlice):
				$pts = $difference->intermediate_state->pts;
				$date = $difference->intermediate_state->date;
				$qts = $difference->intermediate_state->qts;
			elseif($difference instanceof \Tak\Liveproto\Tl\Types\Updates\DifferenceTooLong):
				$pts = $deep ? $this->search_pts($pts,$difference->pts,$qts,$date) : $difference->pts;
				continue;
			elseif($difference instanceof \Tak\Liveproto\Tl\Types\Updates\DifferenceEmpty):
				break;
			else:
				throw new Exception('Unknown difference update !');
			endif;
			yield $difference;
		endwhile;
	}
	public function get_channel_difference(
		mixed $channel,
		? object $filter = null,
		int $pts = 1,
		int $limit = 0x7fffffff
	) : \Generator {
		$inputChannel = $this->get_input_peer($channel);
		while(true):
			Logging::log('Channel Difference','pts = '.$pts.' & limit = '.$limit.' & channel id = '.$this->get_peer_id($inputChannel));
			$difference = $this->updates->getChannelDifference(channel : $inputChannel,filter : is_null($filter) ? $this->channelMessagesFilterEmpty() : $filter,pts : $pts,limit : $limit,force : true);
			if($difference instanceof \Tak\Liveproto\Tl\Types\Updates\ChannelDifference):
				$pts = $difference->pts;
			elseif($difference instanceof \Tak\Liveproto\Tl\Types\Updates\ChannelDifferenceTooLong):
				if(isset($difference->dialog->pts) and is_null($difference->final)):
					$pts = $difference->dialog->pts;
					continue;
				else:
					break;
				endif;
			elseif($difference instanceof \Tak\Liveproto\Tl\Types\Updates\ChannelDifferenceEmpty):
				break;
			else:
				throw new Exception('Unknown channel difference update !');
			endif;
			yield $difference;
			if($difference->final) break;
		endwhile;
	}
	private function search_pts(
		int $bottom,
		int $top,
		int $qts,
		int $date
	) : int {
		Logging::log('Difference','Finding PTS...');
		while($bottom <= $top):
			$pts = ($bottom + $top) >> 1;
			try {
				$difference = $this->updates->getDifference(pts : $pts,date : $date,qts : $qts,pts_total_limit : 0x7fffffff,timeout : 3);
			} catch(\Throwable $error){
				Logging::log('Difference',$error->getMessage(),E_WARNING);
				$difference = new \Tak\Liveproto\Tl\Types\Updates\DifferenceTooLong;
			}
			if($difference instanceof \Tak\Liveproto\Tl\Types\Updates\DifferenceTooLong):
				$bottom = $pts + 1;
			else:
				$top = $pts - 1;
			endif;
		endwhile;
		return $bottom;
	}
}

?>