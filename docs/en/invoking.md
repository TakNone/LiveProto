# Invoking

?> Note, Here we want to explain to you how exactly you can use raw [functions](https://tl.liveproto.dev/#/method) and directly exchange your requests between your client and Telegram servers

---

## Object-Oriented Method Chaining

```php
$peer = $client->get_input_peer('@LiveProtoChat');

$text = 'Hello <tg-emoji emoji-id = "5233599134019100925">👋</tg-emoji> | <a href = "https://t.me/LiveProto">LiveProto</a>';

list($message,$entities) = $client->html($text);

var_dump($client->messages->sendMessage(peer : $peer,message : $message,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),entities : $entities));
```

---

```php
$peer = $client->get_input_peer('@LiveProtoChat');

$text = 'Hello [👋](tg://emoji?id=5233599134019100925) | [LiveProto](https://t.me/LiveProto)';

list($message,$entities) = $client->markdown($text);

$namespace = ($client->messages);

var_dump($namespace->sendMessage(peer : $peer,message : $message,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),entities : $entities));
```

---

## Dynamic Method Invocation

```php
$peer = $client->get_input_peer('@LiveProtoChat');

$text = '<u>Hello 👋</u> | <b>LiveProto</b>';

list($message,$entities) = $client->html($text);

var_dump($client('messages.sendMessage',['peer'=>$peer,'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities]));
```

---

```php
$peer = $client->get_input_peer('@LiveProtoChat');

$text = '__Hello 👋__ | **LiveProto**';

list($message,$entities) = $client->markdown($text);

var_dump($client('messages/sendMessage',array('peer'=>$peer,'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities)));
```

---

## Multiple Requests

```php
$peers = ['@LiveProtoChat','@LiveProtoBot'];

$text = 'I am running the <a href = "https://t.me/LiveProto">LiveProto 🌱</a> library';

list($message,$entities) = $client->html($text);

$requests = array();

foreach($peers as $peer){
	$requests []= ['peer'=>$client->get_input_peer($peer),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities];
}

var_dump($client->messages->sendMessageMultiple(...$requests,responses : true,cooldown : 0.05));

$namespace = ($client->messages);

var_dump($namespace->sendMessageMultiple(['peer'=>$client->get_input_peer($peers[false]),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities],['peer'=>$client->get_input_peer($peers[true]),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities],responses : true));
```

---

```php
$peers = ['@LiveProtoChat','@LiveProtoBot'];

$text = 'I am running the [LiveProto 🌱](https://t.me/LiveProto) library';

list($message,$entities) = $client->markdown($text);

$requests = array();

foreach($peers as $peer){
	$requests []= ['peer'=>$client->get_input_peer($peer),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities];
}

var_dump($client('messages.sendMessageMultiple',[...$requests,'responses'=>true]));

var_dump($client('messages/sendMessageMultiple',[array('peer'=>$client->get_input_peer($peers[false]),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities),array('peer'=>$client->get_input_peer($peers[true]),'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX),'entities'=>$entities),'responses'=>true]));
```

### Additional Parameters

> You can use other parameters in your requests, which are managed by the library and provide you with a number of options

| Parameter | Type | Default | Description |
| --- | --- | --- | --- |
| `raw` | `Boolean` | `false` | Gets the method without executing it , Used as parameters ( like [`query:!X`](https://core.telegram.org/api/invoking) ) of some methods |
| `response` | `Boolean` | `true` | If it is false, it means you are not looking for the result of your request and are not waiting for Telegram to notify you of the answer |
| `timeout` | `Integer` | `0` | If zero, it is disabled, otherwise it specifies the maximum time you want to wait for the request result |
| `floodwaitlimit` | `Integer` | `0` | If zero, it is disabled, otherwise you specify the maximum time you want to wait before resending the request if a flood wait error is encountered |
| `extra` | `mixed` | `Null` | If null, it is disabled, otherwise you can add additional information to the response to a specific request |

?> Note, If the flood wait time exceeds the maximum time set, we will not resend it and you will encounter a flood wait error and you will have to handle it yourself , And we also consider the maximum value you set between the `floodwaitlimit` parameter and the [`floodSleepThreshold`](en/configuration.md#flood-sleep-threshold) parameter

---

#### Telegram Business

The [invokeWithBusinessConnection](https://tl.liveproto.dev/#/method/invokeWithBusinessConnection) invoke a method using a Telegram Business Bot connection

```php
var_dump($client->messages->sendMessage(peer : $peer,message : 'A message is sent via Telegram Business Bot',random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),businessConnectionId : 'Abcd...'));
```

#### Disabling updates

The [invokeWithoutUpdates](https://tl.liveproto.dev/#/method/invokeWithoutUpdates) can be used to invoke a request without subscribing the used connection for updates ( this is enabled by default for file queries )

```php
$temp = $client->getTemp(dc_id : 2,expires_in : 24 * 60 * 60);

var_dump($temp->messages->sendMessage(peer : $peer,message : 'We will not track updates in this hypothetical connection',random_id : random_int(PHP_INT_MIN,PHP_INT_MAX),receiveUpdates : false));
```

#### Sequential Requests

By default, the server processes parallel requests in arbitrary order. Two helper methods exist for cases when the client needs certain requests to be processed in a certain order and intends to send a new request before the previous one is completed
These methods allow reducing the latency for calls which require strict ordering, since the client doesn't have to wait for the result of the previous method call before sending the next one in the queue, and can just send them all together

```php
$texts = ['1) First message','2) Second message','3) Third message'];

$inputPeer = $client->get_input_peer('@LiveProtoChat');

$requests = array(['peer'=>$inputPeer,'message'=>'🏁 Start sending messages','random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX)]);

foreach($texts as $message){
	$requests []= ['peer'=>$inputPeer,'message'=>$message,'random_id'=>random_int(PHP_INT_MIN,PHP_INT_MAX)];
}

var_dump($client->messages->sendMessageMultiple(...$requests,responses : true,queued : true));
```

#### Takeout API

pass the appropriate flags to enable usage of the corresponding methods, as [» described](en/configuration.md#Takeout)

```php
$telegram = $client->get_intput_peer('+42777');

$messages = $client->messages->getHistory(
	peer : $telegram,
	offset_id : 0,
	offset_date : 0,
	add_offset : 0,
	limit : 200,
	max_id : 0, // This parameter works if the value is positive //
	min_id : 0, // This parameter works if the value is positive //
	hash : 0,
	takeout : true
);

var_dump($messages);
```