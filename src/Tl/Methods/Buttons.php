<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Crypto\Password;

use Tak\Liveproto\Attributes\Type;

trait Buttons {
	public function create_reply_markup(
		? array $keyboard = null,
		? array $inline_keyboard = null,
		bool $remove_keyboard = false,
		bool $force_reply = false,
		mixed ...$args
	) : object {
		if(is_array($keyboard)):
			return $this->replyKeyboardMarkup(...$args,rows : array_map($this->compose_row(...),$keyboard));
		elseif(is_array($inline_keyboard)):
			return $this->replyInlineMarkup(...$args,rows : array_map($this->compose_row(...),$inline_keyboard));
		elseif($remove_keyboard):
			return $this->replyKeyboardHide(...$args);
		elseif($force_reply):
			return $this->replyKeyboardForceReply(...$args);
		else:
			throw new \InvalidArgumentException('No valid markup configuration provided');
		endif;
	}
	public function compose_row(array $buttons) : object {
		$column = array();
		$approval = fn(array $button,array $requirements,array $optionals = []) : bool => array_all($requirements,fn(string $key) : bool => in_array($key,array_keys($button))) and empty(array_diff(array_keys($button),$requirements,$optionals));
		$stripPrefix = fn(array $data,string $prefix) : array => array_combine(array_map(fn(string $key) : string => str_starts_with($key,$prefix) ? substr($key,strlen($prefix)) : $key,array_keys($data)),$data);
		foreach($buttons as $button):
			if(is_array($button) and array_is_list($button) === false):
				$style = boolval(array_key_exists('style',$button) and is_string($button['style'])) ? strtolower($button['style']) : null;
				$icon = boolval(array_key_exists('icon_custom_emoji_id',$button) and ctype_digit($button['icon_custom_emoji_id'])) ? intval($button['icon_custom_emoji_id']) : null;
				$design = boolval(is_null($style) and is_null($icon)) ? null : $this->keyboardButtonStyle(bg_primary : boolval($style === 'primary'),bg_danger : boolval($style === 'danger'),bg_success : boolval($style === 'success'),icon : $icon);
				unset($button['style'],$button['icon_custom_emoji_id']);
				$column []= match(true){
					$approval($button,['text']) => $this->keyboardButton(
						text : strval($button['text']),
						style : $design
					),
					$approval($button,['text','url']) => $this->keyboardButtonUrl(
						text : strval($button['text']),
						url : $button['url'],
						style : $design
					),
					$approval($button,['text','callback_data'],['requires_password']) => $this->keyboardButtonCallback(
						requires_password : boolval($button['requires_password'] ?? false),
						text : strval($button['text']),
						data : $button['callback_data'],
						style : $design
					),
					$approval($button,['text','request_contact']) => boolval($button['request_contact']) ? $this->keyboardButtonRequestPhone(
						text : strval($button['text']),
						style : $design
					) : $this->keyboardButton(
						text : strval($button['text']),
						style : $design
					),
					$approval($button,['text','request_location']) => boolval($button['request_location']) ? $this->keyboardButtonRequestGeoLocation(
						text : strval($button['text']),
						style : $design
					) : $this->keyboardButton(
						text : strval($button['text']),
						style : $design
					),
					$approval($button,['text','switch_inline_query']) => $this->keyboardButtonSwitchInline(
						text : strval($button['text']),
						query : $button['switch_inline_query'],
						style : $design
					),
					$approval($button,['text','switch_inline_query_current_chat']) => $this->keyboardButtonSwitchInline(
						same_peer : true,
						text : strval($button['text']),
						query : $button['switch_inline_query_current_chat'],
						style : $design
					),
					$approval($button,['text','switch_inline_query_chosen_chat']) => $this->keyboardButtonSwitchInline(
						text : strval($button['text']),
						query : strval($button['switch_inline_query_chosen_chat']['query'] ?? null),
						peer_types : array_filter(array(
							isset($button['switch_inline_query_chosen_chat']['allow_user_chats']) ? $this->inlineQueryPeerTypePM() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_bot_chats']) ? $this->inlineQueryPeerTypeBotPM() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_group_chats']) ? $this->inlineQueryPeerTypeChat() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_group_chats']) ? $this->inlineQueryPeerTypeMegagroup() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_channel_chats']) ? $this->inlineQueryPeerTypeBroadcast() : null
						)),
						style : $design
					),
					$approval($button,['text','callback_game']) => $this->keyboardButtonGame(
						text : strval($button['text']),
						style : $design
					),
					$approval($button,['text','pay']) => boolval($button['pay']) ? $this->keyboardButtonBuy(
						text : strval($button['text']),
						style : $design
					) : $this->keyboardButton(
						text : strval($button['text']),
						style : $design
					),
					$approval($button,['text','login_url']) => $this->inputKeyboardButtonUrlAuth(
						request_write_access : boolval($button['login_url']['request_write_access'] ?? false),
						text : strval($button['text']),
						fwd_text : boolval(is_array($button['login_url']) and array_key_exists('forward_text',$button['login_url'])) ? strval($button['login_url']['forward_text']) : null,
						url : $button['login_url']['url'] ?? throw new \InvalidArgumentException('The login url does not provide url'),
						bot : $this->get_input_user($button['login_url']['bot_username'] ?? 'bot'),
						style : $design
					),
					$approval($button,['text','request_poll']) => $this->keyboardButtonRequestPoll(
						quiz : boolval(is_array($button['request_poll']) and array_key_exists('type',$button['request_poll'])) ? $button['request_poll']['type'] === 'quiz' : false,
						text : strval($button['text']),
						style : $design
					),
					$approval($button,['text','mention_user']) => $this->inputKeyboardButtonUserProfile(
						text : strval($button['text']),
						user_id : $this->get_input_user($button['mention_user']),
						style : $design
					),
					$approval($button,['text','web_app']) => boolval($button['web_app']['is_simple'] ?? false) ? $this->keyboardButtonSimpleWebView(
						text : strval($button['text']),
						url : $button['web_app']['url'] ?? throw new \InvalidArgumentException('The web app does not provide url'),
						style : $design
					) : $this->keyboardButtonWebView(
						text : strval($button['text']),
						url : $button['web_app']['url'] ?? throw new \InvalidArgumentException('The web app does not provide url'),
						style : $design
					),
					$approval($button,['text','request_users']) => $this->inputKeyboardButtonRequestPeer(
						name_requested : boolval($button['request_users']['request_name'] ?? false),
						username_requested : boolval($button['request_users']['request_username'] ?? false),
						photo_requested : boolval($button['request_users']['request_photo'] ?? false),
						text : strval($button['text']),
						button_id : $button['request_users']['request_id'] ?? 0,
						peer_type : $this->requestPeerTypeUser(
							bot : boolval($button['request_users']['user_is_bot'] ?? false),
							premium : boolval($button['request_users']['user_is_premium'] ?? false)
						),
						max_quantity : $button['request_users']['max_quantity'] ?? 1,
						style : $design
					),
					$approval($button,['text','request_chat']) => $this->inputKeyboardButtonRequestPeer(
						name_requested : boolval($button['request_chat']['request_title'] ?? false),
						username_requested : boolval($button['request_chat']['request_username'] ?? false),
						photo_requested : boolval($button['request_chat']['request_photo'] ?? false),
						text : strval($button['text']),
						button_id : $button['request_chat']['request_id'] ?? 0,
						peer_type : boolval($button['request_chat']['chat_is_channel'] ?? false) ? $this->requestPeerTypeBroadcast(
							creator : boolval($button['request_chat']['chat_is_created'] ?? false),
							has_username : boolval($button['request_chat']['chat_has_username'] ?? false),
							user_admin_rights : boolval(is_array($button['request_chat']) and array_key_exists('user_administrator_rights',$button['request_chat'])) ? $this->chatAdminRights(...$stripPrefix($button['request_chat']['user_administrator_rights'],'can_')) : null,
							bot_admin_rights : boolval(is_array($button['request_chat']) and array_key_exists('bot_administrator_rights',$button['request_chat'])) ? $this->chatAdminRights(...$stripPrefix($button['request_chat']['bot_administrator_rights'],'can_')) : null
						) : $this->requestPeerTypeChat(
							creator : boolval($button['request_chat']['chat_is_created'] ?? false),
							bot_participant : boolval($button['request_chat']['bot_is_member'] ?? false),
							has_username : boolval($button['request_chat']['chat_has_username'] ?? false),
							forum : boolval($button['request_chat']['chat_is_forum'] ?? false),
							user_admin_rights : boolval(is_array($button['request_chat']) and array_key_exists('user_administrator_rights',$button['request_chat'])) ? $this->chatAdminRights(...$stripPrefix($button['request_chat']['user_administrator_rights'],'can_')) : null,
							bot_admin_rights : boolval(is_array($button['request_chat']) and array_key_exists('bot_administrator_rights',$button['request_chat'])) ? $this->chatAdminRights(...$stripPrefix($button['request_chat']['bot_administrator_rights'],'can_')) : null
						),
						max_quantity : $button['request_chat']['max_quantity'] ?? 1,
						style : $design
					),
					$approval($button,['text','request_managed_bot']) => $this->inputKeyboardButtonRequestPeer(
						// name_requested : boolval($button['request_managed_bot']['request_name'] ?? false),
						// username_requested : boolval($button['request_managed_bot']['request_username'] ?? false),
						// photo_requested : boolval($button['request_managed_bot']['request_photo'] ?? false),
						text : strval($button['text']),
						button_id : $button['request_managed_bot']['request_id'] ?? 0,
						peer_type : $this->requestPeerTypeCreateBot(
							bot_managed : true,
							suggested_name : $button['request_managed_bot']['suggested_name'] ?? null,
							suggested_username : $button['request_managed_bot']['suggested_username'] ?? null
						),
						max_quantity : $button['request_managed_bot']['max_quantity'] ?? 1,
						style : $design
					),
					$approval($button,['text','copy_text']) => $this->keyboardButtonCopy(
						text : strval($button['text']),
						copy_text : strval($button['copy_text']['text'] ?? $button['text']),
						style : $design
					),
					default => throw new \InvalidArgumentException('The button is in invalid format : '.json_encode($button,flags : JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)) 
				};
			endif;
		endforeach;
		return $this->keyboardButtonRow(buttons : $column);
	}
	protected function click_button(
		#[Type('Message')] object $message,
		? int $i = null,
		? int $j = null,
		? string $text = null,
		? string $data = null,
		? string $query = null,
		? callable $filter = null,
		? string $password = null,
		? array $contact = null,
		? array $geo = null,
		string | int | null | object $user = null
	) : mixed {
		if($message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
			if(is_object($message->reply_markup)):
				$button = $this->get_button($message->reply_markup,$i,$j,$text,$data,$query,$filter);
				$peer = $this->get_input_peer($message->peer_id);
				if($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButton):
					return $this->messages->sendMessage(peer : $peer,message : $button->text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonUrl):
					return @file_get_contents($button->url);
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonCallback):
					if($button->requires_password):
						$password = is_null($password) ? (isset($this->load->password) ? $this->load->password : null) : $password;
						if(is_null($password) === false):
							$account = $this->account->getPassword();
							$checker = new Password();
							$password = $checker->srp($account,$password);
						else:
							throw new \InvalidArgumentException('The password argument is required !');
						endif;
					else:
						$password = null;
					endif;
					return $this->messages->getBotCallbackAnswer(peer : $peer,msg_id : $message->id,data : $button->data,password : $password);
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonRequestPhone):
					if(is_null($contact) === false):
						if(isset($contact['phone'],$contact['firstname'])):
							$contact = $this->inputMediaContact(phone_number : strval($contact['phone']),first_name : strval($contact['firstname']),last_name : strval(isset($contact['lastname']) ? $contact['lastname'] : null),vcard : strval(isset($contact['vcard']) ? $contact['vcard'] : null));
						else:
							throw new \InvalidArgumentException('The contact argument should be an array containing phone and firstname ( lastname & vcard optional ) !');
						endif;
					else:
						$me = $this->get_me();
						$contact = $this->inputMediaContact(phone_number : $me->phone,first_name : $me->first_name,last_name : strval($me->last_name),vcard : strval(null));
					endif;
					return $this->messages->sendMedia(peer : $peer,media : $contact,message : $button->text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonRequestGeoLocation):
					if(is_null($geo) === false):
						if(isset($geo['lat'],$geo['long'])):
							$geo = $this->inputMediaGeoPoint(geo_point : $this->inputGeoPoint(lat : floatval($geo['lat']),long : floatval($geo['long'])));
						else:
							throw new \InvalidArgumentException('The geo argument should be an array containing lat and long !');
						endif;
					else:
						throw new \InvalidArgumentException('The geo argument is required !');
					endif;
					return $this->messages->sendMedia(peer : $peer,media : $geo,message : $button->text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonSwitchInline):
					if($button->same_peer):
						$bot = is_int($message->via_bot_id) ? $this->get_input_peer($message->via_bot_id) : $peer;
					elseif(is_null($user) === false):
						$bot = is_int($message->via_bot_id) ? $this->get_input_peer($message->via_bot_id) : $peer;
						$peer = $this->get_input_peer($user);
					else:
						throw new \InvalidArgumentException('The user argument is required !');
					endif;
					return $this->inline_query(bot : $bot,query : $button->query,peer : $peer);
					# return $this->messages->startBot(bot : $this->get_input_peer($message->via_bot_id),peer : $peer,start_param : $button->query,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonGame):
					return $this->messages->getBotCallbackAnswer(peer : $peer,msg_id : $message->id,game : true);
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonRequestPeer):
					if(is_null($user) === false):
						$requested = $this->get_input_peer($user);
					else:
						throw new \InvalidArgumentException('The user argument is required !');
					endif;
					return $this->messages->sendBotRequestedPeer(peer : $peer,msg_id : $message->id,button_id : $button->button_id,requested_peer : $requested);
				elseif($button instanceof \Tak\Liveproto\Tl\Types\Other\KeyboardButtonCopy):
					return $button->copy_text;
				else:
					throw new \Exception('Unsupported button type !');
				endif;
			else:
				throw new \InvalidArgumentException('Your message does not contain reply markup !');
			endif;
		else:
			throw new \InvalidArgumentException('The message is invalid !');
		endif;
	}
	protected function get_button(
		#[Type('ReplyMarkup')] object $reply_markup,
		? int $i = null,
		? int $j = null,
		? string $text = null,
		? string $data = null,
		? string $query = null,
		? callable $filter = null
	) : object {
		$index = (is_null($i) === false and is_null($j)) ? $i : null;
		$x = 0;
		$y = 0;
		if($reply_markup instanceof \Tak\Liveproto\Tl\Types\Other\ReplyKeyboardMarkup or $reply_markup instanceof \Tak\Liveproto\Tl\Types\Other\ReplyInlineMarkup):
			foreach($reply_markup->rows as $row):
				foreach($row->buttons as $button):
					if(is_null($index) === false and $index === ($x + $y)):
						return $button;
					elseif($i === $x and $j === $y):
						return $button;
					elseif(is_null($text) === false and $button->text === $text):
						return $button;
					elseif(is_null($data) === false and $button->data === $data):
						return $button;
					elseif(is_null($query) === false and $button->query === $query):
						return $button;
					elseif(is_null($filter) === false and $filter($button)):
						return $button;
					endif;
					$y++;
				endforeach;
				$x++;
			endforeach;
		else:
			throw new \InvalidArgumentException('The reply markup must be an object of replyKeyboardMarkup / replyInlineMarkup !');
		endif;
		throw new \Exception('The button you wanted was not found !');
	}
}

?>