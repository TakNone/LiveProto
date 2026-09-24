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
			return $this->replyKeyboardMarkup(...$args,force_reply : $force_reply,rows : array_map(fn(array $row) : object => $this->compose_row(buttons : $row,inline : false),$keyboard));
		elseif(is_array($inline_keyboard)):
			return $this->replyInlineMarkup(...$args,force_reply : $force_reply,rows : array_map(fn(array $row) : object => $this->compose_row(buttons : $row,inline : true),$inline_keyboard));
		elseif($remove_keyboard):
			return $this->replyKeyboardHide(...$args);
		elseif($force_reply):
			return $this->replyKeyboardForceReply(...$args);
		else:
			throw new \InvalidArgumentException('No valid markup configuration provided');
		endif;
	}
	public function compose_row(array $buttons,bool $inline = false) : object {
		$column = array();
		$approval = fn(array $button,array $requirements,array $optionals = []) : bool => array_all($requirements,fn(string $key) : bool => in_array($key,array_keys($button))) and empty(array_diff(array_keys($button),$requirements,$optionals));
		$stripPrefix = fn(array $data,string $prefix) : array => array_combine(array_map(fn(string $key) : string => str_starts_with($key,$prefix) ? substr($key,strlen($prefix)) : $key,array_keys($data)),$data);
		foreach($buttons as $button):
			if(is_array($button) and array_is_list($button) === false):
				$style = boolval(array_key_exists('style',$button) and is_string($button['style'])) ? strtolower($button['style']) : null;
				$icon = boolval(array_key_exists('icon_custom_emoji_id',$button) and ctype_digit($button['icon_custom_emoji_id'])) ? intval($button['icon_custom_emoji_id']) : null;
				$design = boolval(is_null($style) and is_null($icon)) ? null : $this->keyboardButtonStyle(bg_primary : boolval($style === 'primary'),bg_danger : boolval($style === 'danger'),bg_success : boolval($style === 'success'),icon : $icon);
				$disabled = boolval($button['disabled'] ?? false);
				unset($button['style'],$button['icon_custom_emoji_id'],$button['disabled']);
				$type = boolval($disabled and $inline) ? $this->inlineButtonTypeDisabled() : match(true){
					$approval($button,['text']) => $this->buttonTypeDefault(),
					$approval($button,['text','url']) => $inline ? $this->inlineButtonTypeUrl(
						url : $button['url']
					) : throw new \InvalidArgumentException('The url button is only valid in an inline keyboard'),
					$approval($button,['text','callback_data'],['requires_password']) => $inline ? $this->inlineButtonTypeCallback(
						requires_password : boolval($button['requires_password'] ?? false),
						data : $button['callback_data']
					) : throw new \InvalidArgumentException('The callback button is only valid in an inline keyboard'),
					$approval($button,['text','request_contact']) => boolval($button['request_contact']) ? $this->buttonTypeRequestPhone() : $this->buttonTypeDefault(),
					$approval($button,['text','request_location']) => boolval($button['request_location']) ? $this->buttonTypeRequestGeoLocation() : $this->buttonTypeDefault(),
					$approval($button,['text','switch_inline_query']) => $inline ? $this->inlineButtonTypeSwitchInline(
						query : $button['switch_inline_query']
					) : throw new \InvalidArgumentException('The switch inline button is only valid in an inline keyboard'),
					$approval($button,['text','switch_inline_query_current_chat']) => $inline ? $this->inlineButtonTypeSwitchInline(
						same_peer : true,
						query : $button['switch_inline_query_current_chat']
					) : throw new \InvalidArgumentException('The switch inline button is only valid in an inline keyboard'),
					$approval($button,['text','switch_inline_query_chosen_chat']) => $inline ? $this->inlineButtonTypeSwitchInline(
						query : strval($button['switch_inline_query_chosen_chat']['query'] ?? null),
						peer_types : array_filter(array(
							isset($button['switch_inline_query_chosen_chat']['allow_user_chats']) ? $this->inlineQueryPeerTypePM() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_bot_chats']) ? $this->inlineQueryPeerTypeBotPM() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_group_chats']) ? $this->inlineQueryPeerTypeChat() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_group_chats']) ? $this->inlineQueryPeerTypeMegagroup() : null,
							isset($button['switch_inline_query_chosen_chat']['allow_channel_chats']) ? $this->inlineQueryPeerTypeBroadcast() : null
						))
					) : throw new \InvalidArgumentException('The switch inline button is only valid in an inline keyboard'),
					$approval($button,['text','callback_game']) => $inline ? $this->inlineButtonTypeGame() : throw new \InvalidArgumentException('The game button is only valid in an inline keyboard'),
					$approval($button,['text','pay']) => boolval($button['pay']) ? ($inline ? $this->inlineButtonTypeBuy() : throw new \InvalidArgumentException('The pay button is only valid in an inline keyboard')) : ($inline ? $this->inlineButtonTypeDisabled() : $this->buttonTypeDefault()),
					$approval($button,['text','login_url']) => $inline ? $this->inputInlineButtonTypeUrlAuth(
						request_write_access : boolval($button['login_url']['request_write_access'] ?? false),
						fwd_text : boolval(is_array($button['login_url']) and array_key_exists('forward_text',$button['login_url'])) ? strval($button['login_url']['forward_text']) : null,
						url : $button['login_url']['url'] ?? throw new \InvalidArgumentException('The login url does not provide url'),
						bot : $this->get_input_user($button['login_url']['bot_username'] ?? 'bot')
					) : throw new \InvalidArgumentException('The login url button is only valid in an inline keyboard'),
					$approval($button,['text','request_poll']) => $this->buttonTypeRequestPoll(
						quiz : boolval(is_array($button['request_poll']) and array_key_exists('type',$button['request_poll'])) ? $button['request_poll']['type'] === 'quiz' : false
					),
					$approval($button,['text','mention_user']) => $inline ? $this->inputInlineButtonTypeUserProfile(
						user_id : $this->get_input_user($button['mention_user'])
					) : throw new \InvalidArgumentException('The mention user button is only valid in an inline keyboard'),
					$approval($button,['text','web_app']) => $inline ? $this->inlineButtonTypeWebView(
						url : $button['web_app']['url'] ?? throw new \InvalidArgumentException('The web app does not provide url')
					) : $this->buttonTypeSimpleWebView(
						url : $button['web_app']['url'] ?? throw new \InvalidArgumentException('The web app does not provide url')
					),
					$approval($button,['text','request_users']) => $this->inputButtonTypeRequestPeer(
						name_requested : boolval($button['request_users']['request_name'] ?? false),
						username_requested : boolval($button['request_users']['request_username'] ?? false),
						photo_requested : boolval($button['request_users']['request_photo'] ?? false),
						button_id : $button['request_users']['request_id'] ?? 0,
						peer_type : $this->requestPeerTypeUser(
							bot : boolval($button['request_users']['user_is_bot'] ?? false),
							premium : boolval($button['request_users']['user_is_premium'] ?? false)
						),
						max_quantity : $button['request_users']['max_quantity'] ?? 1
					),
					$approval($button,['text','request_chat']) => $this->inputButtonTypeRequestPeer(
						name_requested : boolval($button['request_chat']['request_title'] ?? false),
						username_requested : boolval($button['request_chat']['request_username'] ?? false),
						photo_requested : boolval($button['request_chat']['request_photo'] ?? false),
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
						max_quantity : $button['request_chat']['max_quantity'] ?? 1
					),
					$approval($button,['text','request_managed_bot']) => $this->buttonTypeRequestPeer(
						button_id : $button['request_managed_bot']['request_id'] ?? 0,
						peer_type : $this->requestPeerTypeCreateBot(
							bot_managed : true,
							suggested_name : $button['request_managed_bot']['suggested_name'] ?? null,
							suggested_username : $button['request_managed_bot']['suggested_username'] ?? null
						),
						max_quantity : $button['request_managed_bot']['max_quantity'] ?? 1
					),
					$approval($button,['text','copy_text']) => $inline ? $this->inlineButtonTypeCopy(
						copy_text : strval($button['copy_text']['text'] ?? $button['text'])
					) : throw new \InvalidArgumentException('The copy button is only valid in an inline keyboard'),
					default => throw new \InvalidArgumentException('The button is in invalid format : '.json_encode($button,flags : JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT))
				};
				$column []= $inline ? $this->keyboardInlineButton(
					style : $design,
					text : strval($button['text']),
					type : $type
				) : $this->keyboardButton(
					style : $design,
					text : strval($button['text']),
					type : $type
				);
			endif;
		endforeach;
		return $inline ? $this->keyboardInlineButtonRow(buttons : $column) : $this->keyboardButtonRow(buttons : $column);
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
		? array $poll = null,
		string $platform = 'android',
		string | int | null | object $chat = null
	) : mixed {
		if($message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
			if(is_object($message->reply_markup)):
				$button = $this->get_button($message->reply_markup,$i,$j,$text,$data,$query,$filter);
				$peer = $this->get_input_peer($message->peer_id);
				if($button->type instanceof \Tak\Liveproto\Tl\Types\Other\ButtonTypeDefault):
					return $this->messages->sendMessage(peer : $peer,message : $button->text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeUrl):
					return @file_get_contents($button->type->url);
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeUrlAuth):
					$result = $this->messages->requestUrlAuth(peer : $peer,msg_id : $message->id,button_id : $button->type->button_id);
					if($result instanceof \Tak\Liveproto\Tl\Types\Other\UrlAuthResultRequest):
						return $this->messages->acceptUrlAuth(peer : $peer,msg_id : $message->id,button_id : $button->type->button_id,write_allowed : boolval($result->request_write_access));
					else:
						return $result;
					endif;
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeCallback):
					if($button->type->requires_password):
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
					return $this->messages->getBotCallbackAnswer(peer : $peer,msg_id : $message->id,data : $button->type->data,password : $password);
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\ButtonTypeRequestPhone):
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
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\ButtonTypeRequestGeoLocation):
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
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\ButtonTypeRequestPoll):
					if(is_null($poll) === false):
						if(isset($poll['question'],$poll['answers']) and is_array($poll['answers'])):
							$media = $this->inputMediaPoll(poll : $this->poll(...$poll,id : random_int(PHP_INT_MIN,PHP_INT_MAX),quiz : boolval($button->type->quiz),hash : 0));
						else:
							throw new \InvalidArgumentException('The poll argument should be an array containing question and answers !');
						endif;
					else:
						throw new \InvalidArgumentException('The poll argument is required !');
					endif;
					return $this->messages->sendMedia(peer : $peer,media : $media,message : $button->text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\ButtonTypeSimpleWebView):
					$bot = is_int($message->via_bot_id) ? $this->get_input_peer($message->via_bot_id) : $this->get_input_peer($message->peer_id);
					return $this->messages->requestSimpleWebView(bot : $bot,url : $button->type->url,platform : $platform);
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeWebView):
					$bot = is_int($message->via_bot_id) ? $this->get_input_peer($message->via_bot_id) : $peer;
					return $this->messages->requestWebView(peer : $peer,bot : $bot,url : $button->type->url,platform : $platform);
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\ButtonTypeRequestPeer):
					if(is_null($chat) === false):
						$requested = $this->get_input_peer($chat);
					else:
						throw new \InvalidArgumentException('The user argument is required !');
					endif;
					return $this->messages->sendBotRequestedPeer(peer : $peer,msg_id : $message->id,button_id : $button->type->button_id,requested_peers : array($requested));
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeSwitchInline):
					if($button->type->same_peer):
						$bot = is_int($message->via_bot_id) ? $this->get_input_peer($message->via_bot_id) : $peer;
					elseif(is_null($chat) === false):
						$bot = is_int($message->via_bot_id) ? $this->get_input_peer($message->via_bot_id) : $peer;
						$peer = $this->get_input_peer($chat);
					else:
						throw new \InvalidArgumentException('The user argument is required !');
					endif;
					return $this->inline_query(bot : $bot,query : $button->type->query,peer : $peer);
					# return $this->messages->startBot(bot : $this->get_input_peer($message->via_bot_id),peer : $peer,start_param : $button->type->query,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeGame):
					return $this->messages->getBotCallbackAnswer(peer : $peer,msg_id : $message->id,game : true);
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeBuy):
					return $this->payments->getPaymentForm(invoice : $this->inputInvoiceMessage(peer : $peer,msg_id : $message->id));
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeUserProfile):
					return $this->users->getFullUser(id : $this->get_input_user($button->type->user_id));
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeCopy):
					return $button->type->copy_text;
				elseif($button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeDisabled):
					throw new \Exception('This button is disabled and cannot be clicked !');
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
					elseif(is_null($data) === false and $button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeCallback and $button->type->data === $data):
						return $button;
					elseif(is_null($query) === false and $button->type instanceof \Tak\Liveproto\Tl\Types\Other\InlineButtonTypeSwitchInline and $button->type->query === $query):
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