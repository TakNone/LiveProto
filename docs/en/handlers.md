# Handling Updates

> [!TIP]
> Okay, now let's move on to seeing how to handle the updates that are coming in and how to work with the event filter. This section is very interesting :)

To start , let me tell you how to add a function or method from a class to the list of update recipients

---

## Custom Event Types

The namespace for these is `Tak\Liveproto\Filters\Events\__X__`

### CallbackQuery

This object represents an incoming callback query from a callback button in an inline keyboard

An incoming callback query is sent when a user presses an inline button with a `callback_data` field attached

### ChosenInlineResult

Represents a result of an inline query that was chosen by the user and sent to their chat partner

This object is sent when a user *chooses* one of your bot’s inline query results and sends it into the chat

### InlineQuery

This object represents an incoming inline query. When the user sends an empty query, your bot could return some default or trending results

Represents an incoming inline query, sent when a user types `@LiveProtoBot ...` in any chat

### NewMessage

This object represents a message

An update representing a newly received message of any kind

### NewScheduledMessage

This object represents a message

Fired when a scheduled message ( set up via `sendMessage` with `schedule_date` ) is actually posted

### MessageEdited

This object represents a message

Sent when a message is edited ( text or media caption )

### NewJoinRequest

Represents a join request sent to a chat

Occurs when a user requests to join a **channel** or **group** that has “approve new members” enabled

### NewStory

Represents a new story from the peer

You will receive this event when someone shares a story on their channel / account / group profile 

### Precheckout

This object contains information about an incoming pre-checkout query

---

## Subscribe for updates

You have several ways here that I will introduce to you

?> Note, I recommend that you study the [bound methods](en/bound.md) first to understand what you can do with updates !

---

### Function

```php
use Tak\Liveproto\Filters\Filter;
use Tak\Liveproto\Filters\Events\NewMessage;

#[Filter(new NewMessage())]
function newMessage(Incoming & IsPrivate $update) : void {
	$update->reaction('❤️');
}

// or //

function callbackQuery(IsPrivate $update) : void {
	$update->reaction('🔥');
}

$client->addHandler(callbackQuery(...),'unique_name',new CallbackQuery());

$client->start(); // You must start the client to receive updates
```
---

### Closure

```php
use Tak\Liveproto\Filters\Events\CallbackQuery;
use Tak\Liveproto\Filters\Events\NewMessage;

$closure = function(object $event) : void {
	$event->reaction('👎');
};

$client->addHandler($closure,'unique_name',new CallbackQuery());

// or //

foreach(['🔥','👍','❤️'] as $heart){
	$closure = fn(object $update) => $update->reaction($heart);
	/*
	All three closures live on the same line in your file, and strval(ReflectionFunction) only includes the file name and line range of the function
	So we need to come up with a unique name for them
	To differentiate them so that the handler doesn't identify them all as the same
	*/
	$client->addHandler($closure,strval('unique_name_with_'.$heart),new NewMessage());
}

$client->start(); // You must start the client to receive updates
```
---

### Class Methods

```php
use Tak\Liveproto\Filters\Filter;
use Tak\Liveproto\Filters\Events\NewMessage;

final class UpdateHandler {
	#[Filter(new NewMessage())]
	public function newMessage(Incoming & IsPrivate $update) : void {
		$update->reaction('🔥');
	}
	#[Filter(new CallbackQuery())]
	public function callbackQuery(IsPrivate $update) : void {
		$update->reaction('❤️');
	}
}

$client->addHandler(new UpdateHandler);

$client->start(); // You must start the client to receive updates
```

---

!> Note that handlers without type event have <mark>no bound method</mark> and are completely raw !

---

#### Get All Raw Updates

```php
use Tak\Liveproto\Filters\Filter;

// 1) function

#[Filter]
function updates(object $update) : void {
	print 'function : ';
	var_dump($update);
}

// 2) closure

$closure = function(object $update) : void {
	print 'closure : ';
	var_dump($update);
};

$client->addHandler($closure);

// 3) class method

final class AllUpdates {
	#[Filter]
	public function recipient(object $update) : void {
		print 'method : ';
		var_dump($update);
	}
}

$client->addHandler(new AllUpdates);

$client->start(); // You must start the client to receive updates
```

---

## Update Filter Interfaces

The namespace for these is `Tak\Liveproto\Filters\Interfaces\__X__`

| Filter Interface | What It Matches |
| :---: | :---: |
| **Outgoing** | Messages you sent ( `message.out === true` ) |
| **Incoming** | Messages you received ( `message.out === false` ) |
| **IsMedia** | Any message that contains **any** media ( photo, video, document, etc. ) |
| **IsNotMedia** | Messages **without** any media |
| **IsReply** | Messages that are **replies** ( `message.reply_to` set ) |
| **IsNotReply** | Messages that are **not** replies |
| **IsViaBot** | Messages sent **via another bot** ( `message.via_bot_id` set ) |
| **IsNotViaBot** | Messages **not** sent via a bot |
| **IsEdited** | Messages that have been **edited** ( `message.edit_date` set ) |
| **IsNotEdited** | Messages that have **not** been edited |
| **IsHideEdited** | Messages with **hidden** edits ( `message.edit_hide === true` ) |
| **IsNotHideEdited** | Messages where edits are **visible** |
| **IsQuickReply** | Messages that used a **quick‑reply shortcut** ( `message.quick_reply_shortcut_id` ) |
| **IsNotQuickReply** | Messages **not** sent via a quick‑reply |
| **IsMentioned** | Messages where your bot/user was **mentioned** ( `message.mentioned === true` ) |
| **IsNotMentioned** | Messages **not** mentioning your bot/user |
| **IsSilent** | Silent messages ( `message.silent === true`, no notification ) |
| **IsNotSilent** | Messages that **do** trigger notifications |
| **IsPost** | Channel **posts** ( `message.post === true` ) |
| **IsNotPost** | Messages that are **not** channel posts |
| **IsSuggest** | Channel **suggested posts** ( `message.suggested_post` ) |
| **IsNotSuggest** | Messages that are **not** channel suggested posts |
| **IsPinned** | Messages that are **pinned** in a chat ( `message.pinned === true` ) |
| **IsNotPinned** | Messages that are **not** pinned |
| **IsForwarded** | Messages that were **forwarded** ( `message.fwd_from` set ) |
| **IsNotForwarded** | Messages **not** forwarded |
| **IsBusiness** | “Business” messages ( containing a `connection_id` ) |
| **IsNotBusiness** | Messages **without** a `connection_id` |
| **IsPrivate** | Updates originating in **private** chats ( 1 : 1 ) |
| **IsNotPrivate** | Updates **not** in private chats ( groups, channels ) |
| **IsGroup** | Updates in a **basic group** |
| **IsNotGroup** | Updates **not** in basic groups |
| **IsSuperGroup** | Updates in a **supergroup** |
| **IsNotSuperGroup** | Updates **not** in supergroups |
| **IsChannel** | Updates in a **channel** ( broadcast ) |
| **IsNotChannel** | Updates **not** in channels |
| **IsBot** | Messages sent by **bots** |
| **IsNotBot** | Messages sent by **users** |
| **IsSelf** | Messages sent by **this bot/self** |
| **IsNotSelf** | Messages sent by **others** |
| **HasEntity** | Messages that contain any **text entity** ( `message.entities` ) |
| **HasNotEntity** | Messages **without** text entities |
| **HasReaction** | Messages with **reactions** ( `message.reactions` ) |
| **HasNotReaction** | Messages **without** reactions |
| **HasReplyMarkup** | Messages that include an **inline reply markup** ( `message.reply_markup` ) |
| **HasNotReplyMarkup** | Messages **without** inline keyboards |
| **HasPhoto** | Messages containing a **photo** |
| **HasNotPhoto** | Messages **without** photos |
| **HasGeo** | Messages containing **raw geo** ( `MessageMediaGeo` ) |
| **HasNotGeo** | Messages **without** raw geo media |
| **HasGeoLive** | Messages containing **live location** ( `MessageMediaGeoLive` ) |
| **HasNotGeoLive** | Messages **without** live location |
| **HasContact** | Messages containing a **contact card** |
| **HasNotContact** | Messages **without** contact cards |
| **HasDocument** | Messages containing a **document** ( any file ) |
| **HasNotDocument** | Messages **without** documents |
| **HasWebPage** | Messages containing a **web page preview** |
| **HasNotWebPage** | Messages **without** web page previews |
| **HasVenue** | Messages containing a **venue** ( `MessageMediaVenue` ) |
| **HasNotVenue** | Messages **without** venues |
| **HasGame** | Messages containing a **game** |
| **HasNotGame** | Messages **without** games |
| **HasInvoice** | Messages containing an **invoice** ( payment ) |
| **HasNotInvoice** | Messages **without** invoices |
| **HasPoll** | Messages containing a **poll** |
| **HasNotPoll** | Messages **without** polls |
| **HasDice** | Messages containing a **dice** roll |
| **HasNotDice** | Messages **without** dice rolls |
| **HasStory** | Messages containing a **story** update |
| **HasNotStory** | Messages **without** story updates |
| **Message** | Any **standard message** update |
| **NotMessage** | Any update **other than** a standard message |
| **Callback** | Any **callback query** ( `UpdateBotCallbackQuery` ) |
| **NotCallback** | Updates **excluding** callback queries |
| **Inline** | Any **inline query** ( `UpdateBotInlineQuery` ) |
| **NotInline** | Updates **excluding** inline queries |

---

### Usage Of Interfaces

!> Some of these examples are unrealistic and may not have happened at all, and this is purely for practice and learning

```php

/**
 * Incoming plain-text private messages only
 */
function onPrivateText(Incoming & IsPrivate & IsNotMedia $update) : void {
	// Fires when a user sends a text-only message in a 1:1 chat
}

/**
 * web-page preview in channel or PM
 */
function onPreviewShared((HasWebPage & IsPrivate) | (HasWebPage & IsChannel) $update) : void {
	// Fires when a link with preview is sent to you or your channel
}

/**
 * Incoming group messages that are replies and contain documents
 */
function onGroupReplyDocument(Incoming & IsGroup & IsReply & HasDocument $update) : void {
	// Fires when someone replies to a message in a basic group with a file
}

/**
 * Invoice or poll in channels
 */
function onSaleOrSurvey((HasInvoice & IsChannel) | (HasPoll & IsChannel) $update) : void {
	// Fires when invoices or polls publish in channels
}

/**
 * Outgoing channel posts with any media
 */
function onOwnChannelMedia(Outgoing & IsChannel & IsMedia $update) : void {
	// Fires when *you* post media to one of your channels
}

/**
 * Inline queries from non‑private chats (eg, groups, channels)
 */
function onGroupInline(Inline & IsNotPrivate $update) : void {
	// Fires when someone invokes your bot inline outside a 1:1 chat
}

/**
 * Incoming private messages with live‑location updates
 */
function onPrivateLiveLocation(Incoming & IsPrivate & HasGeoLive $update) : void {
	// Fires when a user shares or updates their live location in a private chat
}

/**
 * Outgoing private messages that include contact cards
 */
function onOwnContactShare(Outgoing & IsPrivate & HasContact $update) : void {
	// Fires when you share someone’s contact in a private chat
}

/**
 * Mention in channel or supergroup
 */
function onMentionInChannels((Incoming & IsMentioned & IsChannel) | (Incoming & IsMentioned & IsSuperGroup) $update) : void {
	// Fires when the bot is mentioned in a channel or supergroup
}

/**
 * Incoming private messages with inline keyboards
 */
function onPrivateInlineKeyboard(Incoming & IsPrivate & HasReplyMarkup $update) : void {
	// Fires when someone sends a message in private that includes an inline keyboard
}

```

---

## Update Filter Attributes

The namespace for these is `Tak\Liveproto\Filters\Filter\__X__`

| **Filter Class** | **Purpose** | **Constructor Parameters** | **Behavior / Return Value** |
| :---: | :---: | :---: | :---: |
| **Update** | Matches on the specific TL‑type of incoming update. Use this when you only want to process, say, `UpdateNewMessage`, `UpdateBotCallbackQuery`, etc. | `string ...$updates` one or more class basenames (e.g. `"UpdateNewMessage"`, `"UpdateBotCallbackQuery"`) | Returns the `$update` object if its class name (case‑insensitive) is one of the supplied names, otherwise returns `false` |
| **Command** | Filters messages for bot commands (e.g. `/start`, `/help`) and captures their arguments | `string ...$commands` keys are command names ( without slash ), values ( optional ) are argument‐labels ( It can be an array or even a singleton, and it can be a string or an enum [`CommandType`](en/enums.md#CommandType) ) | Internally builds regexes like `^\/(?<cmd>NAME)(?:\s+(?<args>.*))?$` , then delegates to **Regex** Returns the captured matches array on success , `false` if no command matched |
| **Chats** | Limits processing to updates from specific chat or user IDs. Useful for whitelisting / blacklisting chats. | `int ...$ids` one or more Telegram peer IDs ( user / chat / channel IDs ) | Checks `$update->message->peer_id` ( or callback sender, etc. ) against the list, returns `true` if there’s a match, else `false` |
| **Regex** | Applies arbitrary PHP regex patterns to the textual content of various update types ( message text, inline query, callback data, draft, story caption ) | `string ...$patterns` one or more PCRE patterns (e.g. `'/foo/i'`) | Stores all patterns in `$update->regex->patterns`, runs them against the appropriate property, and if any match returns the merged captures array, otherwise returns `false` |

---

### Usage Of Attributes

```php
use Tak\Liveproto\Filters\Filter;
use Tak\Liveproto\Filters\Events\NewMessage;
use Tak\Liveproto\Filters\Events\MessageEdited;
use Tak\Liveproto\Filters\Events\CallbackQuery;
use Tak\Liveproto\Filters\Events\InlineQuery;

use Tak\Liveproto\Filters\Filter\Regex;
use Tak\Liveproto\Filters\Filter\Chats;
use Tak\Liveproto\Filters\Filter\Command;
use Tak\Liveproto\Filters\Filter\Update;

use Tak\Liveproto\Filters\Interfaces\Incoming;
use Tak\Liveproto\Filters\Interfaces\IsPrivate;
use Tak\Liveproto\Filters\Interfaces\IsSuperGroup;
use Tak\Liveproto\Filters\Interfaces\IsSelf;
use Tak\Liveproto\Filters\Interfaces\Inline;

use Tak\Liveproto\Enums\CommandType;

/*
 * A new message should be included wherever the `/start` command is required
 */
/*
 * The update must be incoming and be in a private chat
 */
#[Filter(new NewMessage(new Command('start')))]
function newMessage(Incoming & IsPrivate $update) : void {
	list($message,$entities) = $update->markdown('👋 **__Hello__** , _welcome to ||the bot developed with||_ [LiveProto](https://t.me/LiveProtoChat) !');
	$replymarkup = $update->replyInlineMarkup(rows : array(
		$update->keyboardInlineButtonRow(buttons : array(
			$update->keyboardInlineButton(text : 'callback button',type : $update->inlineButtonTypeCallback(data : 'test callback')),$update->keyboardInlineButton(text : 'url button',type : $update->inlineButtonTypeUrl(url : 'https://telegram.org'))
		)),
		$update->keyboardInlineButtonRow(buttons : array(
			$update->keyboardInlineButton(text : 'switch button',type : $update->inlineButtonTypeSwitchInline(query : 'switch query'))
		)),
	));
	$update->reply(message : $message,entities : $entities,reply_markup : $replymarkup);
}

/*
 * The update must be an inline query and the query must match according to regex `~^switch\s(.+)$~`
 */
/*
 * The update should be in private chat and also somewhere I can see it ( IsSelf )
 */
#[Filter(new InlineQuery(new Regex('~^switch\s(?<sth>.+)$~')))]
function inlineQuery(IsSelf | IsPrivate $update) : void {
	$sth = $update->regex->matched['sth'];
	$me = $update->get_me();
	/*
	Or you can do that...
	$me = $update->getClient()->get_me();
	*/
	list($message,$entities) = $update->html('😉 Your input : <q>'.htmlspecialchars($sth,ENT_HTML5).'</q>');
	$replymarkup = $update->replyInlineMarkup(rows : array(
		$update->keyboardInlineButtonRow(buttons : array(
			$update->keyboardInlineButton(text : 'Hi',type : $update->inlineButtonTypeCallback(data : '/Hello World')),$update->keyboardInlineButton(text : 'go to bot',type : $update->inlineButtonTypeUrl(url : 'https://t.me/'.$me->username))
		)),
		$update->keyboardInlineButtonRow(buttons : array(
			$update->keyboardInlineButton(text : 'switch button',type : $update->inlineButtonTypeSwitchInline(query : 'switch query',same_peer : true))
		)),
	));
	$results = array(
		$update->inputBotInlineResult(
			id : 'first',
			type : 'article',
			title : 'Test One',
			description : 'Hello World !',
			send_message : $update->inputBotInlineMessageText(message : $message,entities : $entities,reply_markup : $replymarkup)
		),
		$update->inputBotInlineResult(
			id : 'second',
			type : 'article',
			title : 'Test Two',
			description : 'Bye World !',
			send_message : $update->inputBotInlineMessageText(message : $message,entities : $entities)
		)
	);
	$update->answer(results : $results,cache : 0,switch_text : 'Start Bot');
}

/*
 * The update must be a callback query type and contain a `/Hello` command
 * OR
 * The update must be a callback query type and the data must match the regex `~(.+)\scallback$~`
 */
/*
 * The update must be inline and the event must be in private chat
 */
#[Filter(new CallbackQuery(new Command('Hello')))]
#[Filter(new CallbackQuery(new Regex('~(.+)\scallback$~')))]
function callbackQuery(IsPrivate | Inline $update) : void {
	if($update->regex->matched['command'] === 'Hello' and $update->regex->matched['parameter'] === 'World'){
		$update->answer(cache : 10,message : 'Hello buddy 🙃',alert : true);
	} else {
		$me = $update->get_me();
		$update->answer(cache : 0,url : 'https://t.me/'.$me->username.'?start=xxx');
	}
}

/*
 * The message must be from `7605884183` ID (@TakNone)
 * AND
 * must also be edited
 * AND
 * must be one of the commands `!pin`, `@pinned`, `/pinned`
 */
/*
 * The update must be incoming and be in a super group
 */
#[Filter(new MessageEdited(new Command(pin : CommandType::EXCLAMATION,pinned : ['@','/']),new Chats(7605884183)))]
function messageEdited(Incoming & IsSuperGroup $update) : void {
	$update->pin();
}

#[Filter(new Update('updateDeleteMessages'))]
function deleteMessages(object $update) : void {
	$client = $update->getClient();
	$peer = $client->get_input_peer('me');
	$text = '⚠️ Someone deleted some message';
	$client->messages->sendMessage(peer : $peer,message : $text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
}

```

---

## Middlewares

Middleware allows you to intercept, inspect, and filter incoming updates before they reach your final handler. This is perfect for cross-cutting logic like authentication, rate-limiting, and role checks

Here are the two ways to implement middleware in LiveProto : **Stacked Attributes** ( Declarative ) and the **Inline Fluent Builder** ( Functional )

---

### Stacked Attributes ( Declarative Style )

```php
use Tak\Liveproto\Filters\Filter;
use Tak\Liveproto\Filters\Events\NewMessage;

use Tak\Liveproto\Filters\Interfaces\Incoming;

// An example of the middlewares you are going to write //

use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Middleware\RoleMiddleware;

final class AdminCommands {
	#[Filter(new NewMessage())]
	#[AuthMiddleware]
	#[RateLimitMiddleware]
	#[RoleMiddleware('admin')]
	public function deleteDatabase(Incoming $update) : void {
		$update->reply('Database successfully purged !');
	}
}

$client->addHandler(new AdminCommands());

$client->start(); // You must start the client to receive updates
```

---

### Inline Fluent Builder ( Functional Style )

```php
$client->addHandler(function(object $update) : void {
	var_dump($update->props);
})->middleware(function(object $update) : object {
	$update->checked = true;
	$update->props = ['You can make any changes you want to the update'];
	if(time() % 2 === 0){
		return $update;
	} else {
		throw new Exception('Condition not met, skipping handler');
	}
});

$client->start(); // You must start the client to receive updates
```

---

## Fetch One Update

Suppose you want to wait for an update and then do your work, well here is a way

```php
/*
 * We wait for 10 seconds to receive update `updateNewEncryptedMessage` or `updateEncryption`
 */
$async = $client->fetchUpdate(updates : array('updateNewEncryptedMessage','updateEncryption'),timeout : 10);

var_dump($async->await());


/*
 * Accept an update whose date is between 10 seconds before and 10 seconds after the current time
 */
function acceptor(object $update) : bool {
	if(isset($update->date)){
		$now = time();
		return boolval(($update->date < $now + 10) and ($update->date > $now - 10));
	} else {
		rerun false;
	}
}
 /*
 * We wait for 10 seconds to receive update `updateEncryption` and a callback that filters updates
 */
$async = $client->fetchUpdate(updates : array('updateEncryption'),callback : acceptor(...),timeout : 10);

var_dump($async->await());
```

---

> [!TIP]
> I know it might be a bit difficult and confusing for you to handle updates using pre-built things in the library, and if you have any questions, you can ask them in the [group](https://t.me/LiveProtoChat)
