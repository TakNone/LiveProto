<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Handlers;

use Tak\Liveproto\Utils\Instance;

use Tak\Liveproto\Filters\Interfaces\Outgoing;

use Tak\Liveproto\Filters\Interfaces\Incoming;

use Tak\Liveproto\Filters\Interfaces\IsMedia;

use Tak\Liveproto\Filters\Interfaces\IsNotMedia;

use Tak\Liveproto\Filters\Interfaces\IsReply;

use Tak\Liveproto\Filters\Interfaces\IsNotReply;

use Tak\Liveproto\Filters\Interfaces\IsViaBot;

use Tak\Liveproto\Filters\Interfaces\IsNotViaBot;

use Tak\Liveproto\Filters\Interfaces\IsEdited;

use Tak\Liveproto\Filters\Interfaces\IsNotEdited;

use Tak\Liveproto\Filters\Interfaces\IsHideEdited;

use Tak\Liveproto\Filters\Interfaces\IsNotHideEdited;

use Tak\Liveproto\Filters\Interfaces\IsQuickReply;

use Tak\Liveproto\Filters\Interfaces\IsNotQuickReply;

use Tak\Liveproto\Filters\Interfaces\IsMentioned;

use Tak\Liveproto\Filters\Interfaces\IsNotMentioned;

use Tak\Liveproto\Filters\Interfaces\IsSilent;

use Tak\Liveproto\Filters\Interfaces\IsNotSilent;

use Tak\Liveproto\Filters\Interfaces\IsPost;

use Tak\Liveproto\Filters\Interfaces\IsNotPost;

use Tak\Liveproto\Filters\Interfaces\IsSuggest;

use Tak\Liveproto\Filters\Interfaces\IsNotSuggest;

use Tak\Liveproto\Filters\Interfaces\IsPinned;

use Tak\Liveproto\Filters\Interfaces\IsNotPinned;

use Tak\Liveproto\Filters\Interfaces\IsForwarded;

use Tak\Liveproto\Filters\Interfaces\IsNotForwarded;

use Tak\Liveproto\Filters\Interfaces\IsBusiness;

use Tak\Liveproto\Filters\Interfaces\IsNotBusiness;

use Tak\Liveproto\Filters\Interfaces\IsPrivate;

use Tak\Liveproto\Filters\Interfaces\IsNotPrivate;

use Tak\Liveproto\Filters\Interfaces\IsGroup;

use Tak\Liveproto\Filters\Interfaces\IsNotGroup;

use Tak\Liveproto\Filters\Interfaces\IsSuperGroup;

use Tak\Liveproto\Filters\Interfaces\IsNotSuperGroup;

use Tak\Liveproto\Filters\Interfaces\IsChannel;

use Tak\Liveproto\Filters\Interfaces\IsNotChannel;

use Tak\Liveproto\Filters\Interfaces\IsBot;

use Tak\Liveproto\Filters\Interfaces\IsNotBot;

use Tak\Liveproto\Filters\Interfaces\IsSelf;

use Tak\Liveproto\Filters\Interfaces\IsNotSelf;

use Tak\Liveproto\Filters\Interfaces\HasEntity;

use Tak\Liveproto\Filters\Interfaces\HasNotEntity;

use Tak\Liveproto\Filters\Interfaces\HasReaction;

use Tak\Liveproto\Filters\Interfaces\HasNotReaction;

use Tak\Liveproto\Filters\Interfaces\HasReplyMarkup;

use Tak\Liveproto\Filters\Interfaces\HasNotReplyMarkup;

use Tak\Liveproto\Filters\Interfaces\HasPhoto;

use Tak\Liveproto\Filters\Interfaces\HasNotPhoto;

use Tak\Liveproto\Filters\Interfaces\HasGeo;

use Tak\Liveproto\Filters\Interfaces\HasNotGeo;

use Tak\Liveproto\Filters\Interfaces\HasContact;

use Tak\Liveproto\Filters\Interfaces\HasNotContact;

use Tak\Liveproto\Filters\Interfaces\HasDocument;

use Tak\Liveproto\Filters\Interfaces\HasNotDocument;

use Tak\Liveproto\Filters\Interfaces\HasWebPage;

use Tak\Liveproto\Filters\Interfaces\HasNotWebPage;

use Tak\Liveproto\Filters\Interfaces\HasVenue;

use Tak\Liveproto\Filters\Interfaces\HasNotVenue;

use Tak\Liveproto\Filters\Interfaces\HasGame;

use Tak\Liveproto\Filters\Interfaces\HasNotGame;

use Tak\Liveproto\Filters\Interfaces\HasInvoice;

use Tak\Liveproto\Filters\Interfaces\HasNotInvoice;

use Tak\Liveproto\Filters\Interfaces\HasGeoLive;

use Tak\Liveproto\Filters\Interfaces\HasNotGeoLive;

use Tak\Liveproto\Filters\Interfaces\HasPoll;

use Tak\Liveproto\Filters\Interfaces\HasNotPoll;

use Tak\Liveproto\Filters\Interfaces\HasDice;

use Tak\Liveproto\Filters\Interfaces\HasNotDice;

use Tak\Liveproto\Filters\Interfaces\HasStory;

use Tak\Liveproto\Filters\Interfaces\HasNotStory;

use Tak\Liveproto\Filters\Interfaces\Message;

use Tak\Liveproto\Filters\Interfaces\NotMessage;

use Tak\Liveproto\Filters\Interfaces\Callback;

use Tak\Liveproto\Filters\Interfaces\NotCallback;

use Tak\Liveproto\Filters\Interfaces\Inline;

use Tak\Liveproto\Filters\Interfaces\NotInline;

final class Events extends Instance implements Outgoing , Incoming , IsMedia , IsNotMedia , IsReply , IsNotReply , IsViaBot , IsNotViaBot , IsEdited , IsNotEdited , IsHideEdited , IsNotHideEdited , IsQuickReply , IsNotQuickReply , IsMentioned , IsNotMentioned , IsSilent , IsNotSilent , IsPost , IsNotPost , IsSuggest , IsNotSuggest , IsPinned , IsNotPinned , IsForwarded , IsNotForwarded , IsBusiness , IsNotBusiness , IsPrivate , IsNotPrivate , IsGroup , IsNotGroup , IsSuperGroup , IsNotSuperGroup , IsChannel , IsNotChannel , IsBot , IsNotBot , IsSelf , IsNotSelf , HasEntity , HasNotEntity , HasReaction , HasNotReaction , HasReplyMarkup , HasNotReplyMarkup , HasPhoto , HasNotPhoto , HasGeo , HasNotGeo , HasContact , HasNotContact , HasDocument , HasNotDocument , HasWebPage , HasNotWebPage , HasVenue , HasNotVenue , HasGame , HasNotGame , HasInvoice , HasNotInvoice , HasGeoLive , HasNotGeoLive , HasPoll , HasNotPoll , HasDice , HasNotDice , HasStory , HasNotStory , Message , NotMessage , Callback , NotCallback , Inline , NotInline {
	static public function copy(Instance $update) : object {
		$event = $update->clone(__CLASS__);
		$event->class = $update->getClass();
		return $event;
	}
}

?>