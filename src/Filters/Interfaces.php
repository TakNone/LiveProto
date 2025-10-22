<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Enums\PeerType;

final class Interfaces {
	public function __construct(private readonly string $name){
	}
	public function check(object $update) : bool {
		$client = $update->getClient();
		$isCustom = $update->is_custom;
		$update->is_custom = true;
		try {
			switch($this->name):
				case 'Tak\\Liveproto\\Filters\\Interfaces\\Outgoing':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->out) === true;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\Incoming':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->out) === false;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsMedia':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotMedia':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsReply':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->reply_to) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotReply':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->reply_to) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsViaBot':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->via_bot_id) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotViaBot':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->via_bot_id) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsEdited':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->edit_date) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotEdited':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->edit_date) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsHideEdited':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->edit_hide) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotHideEdited':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->edit_hide) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsQuickReply':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->quick_reply_shortcut_id) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotQuickReply':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->quick_reply_shortcut_id) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsMentioned':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->mentioned) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotMentioned':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->mentioned) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsSilent':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->silent) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotSilent':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->silent) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsPost':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->post) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotPost':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->post) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsSuggest':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->suggested_post) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotSuggest':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->suggested_post) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsPinned':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->pinned) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotPinned':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->pinned) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsForwarded':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->fwd_from) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotForwarded':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->fwd_from) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsBusiness':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return isset($update->connection_id) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotBusiness':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return isset($update->connection_id) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsPrivate':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() === 'private');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() === 'private');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypePM) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotPrivate':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() !== 'private');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() !== 'private');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypePM) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsGroup':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() === 'group');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() === 'group');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeChat) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotGroup':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() !== 'group');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() !== 'group');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeChat) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsSuperGroup':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() === 'supergroup');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() === 'supergroup');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeMegagroup) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotSuperGroup':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() !== 'supergroup');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() !== 'supergroup');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeMegagroup) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsChannel':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() === 'channel');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() === 'channel');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeBroadcast) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotChannel':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id)->getChatType() !== 'channel');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer)->getChatType() !== 'channel');
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeBroadcast) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsBot':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id) === PeerType::BOT);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer) === PeerType::BOT);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeBotPM) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotBot':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id) !== PeerType::BOT);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer) !== PeerType::BOT);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeBotPM) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsSelf':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id) === PeerType::SELF);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer) === PeerType::SELF);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeSameBotPM) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\IsNotSelf':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($client->get_peer_type($update->message->peer_id) !== PeerType::SELF);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery):
						return boolval($client->get_peer_type($update->peer) !== PeerType::SELF);
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery):
						return boolval($update->peer_type instanceof \Tak\Liveproto\Tl\Types\Other\InlineQueryPeerTypeSameBotPM) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasEntity':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->entities) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return empty($update->entities) === false;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotEntity':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->entities) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return empty($update->entities) === true;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasReaction':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->reactions) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotReaction':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->reactions) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasReplyMarkup':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->reply_markup) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotReplyMarkup':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->reply_markup) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasPhoto':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotPhoto':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPhoto) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasGeo':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeo) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeo) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotGeo':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeo) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeo) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasContact':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotContact':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaContact) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasDocument':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotDocument':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDocument) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasWebPage':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaWebPage) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaWebPage) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotWebPage':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaWebPage) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaWebPage) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasVenue':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaVenue) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaVenue) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotVenue':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaVenue) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaVenue) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasGame':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGame) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGame) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotGame':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGame) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGame) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasInvoice':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaInvoice) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaInvoice) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotInvoice':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaInvoice) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaInvoice) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasGeoLive':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeoLive) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeoLive) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotGeoLive':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeoLive) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaGeoLive) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasPoll':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPoll) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPoll) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotPoll':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPoll) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaPoll) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasDice':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDice) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDice) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotDice':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDice) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaDice) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasStory':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaStory) === true;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaStory) === true;
					else:
						return false;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\HasNotStory':
					if($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message):
						return boolval($update->message->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaStory) === false;
					elseif($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateServiceNotification):
						return boolval($update->media instanceof \Tak\Liveproto\Tl\Types\Other\MessageMediaStory) === false;
					else:
						return true;
					endif;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\Message':
					return boolval($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message) === true;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\NotMessage':
					return boolval($update->message instanceof \Tak\Liveproto\Tl\Types\Other\Message) === false;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\Callback':
					return boolval($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateInlineBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBusinessBotCallbackQuery) === true;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\NotCallback':
					return boolval($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateInlineBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBusinessBotCallbackQuery) === false;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\Inline':
					return boolval($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateInlineBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineSend) === true;
				case 'Tak\\Liveproto\\Filters\\Interfaces\\NotInline':
					return boolval($update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateInlineBotCallbackQuery or $update instanceof \Tak\Liveproto\Tl\Types\Other\UpdateBotInlineSend) === false;
				default:
					$update->is_custom = $isCustom;
					return true;
			endswitch;
		} catch(\Throwable $error){
			Logging::log('Interfaces',$error->getMessage(),E_ERROR);
			$update->is_custom = $isCustom;
			return false;
		}
	}
}

?>