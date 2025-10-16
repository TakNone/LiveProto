# Configuration

> Here we want to explain to you the configuration of the settings of a <mark>LiveProto Client</mark>

---

## Settings

First of all, you need to adjust its settings to your liking

```php
use Tak\Liveproto\Utils\Settings;

$settings = new Settings();


/* Telegram Settings */
$settings->setApiId(29784714);
$settings->setApiHash('143dfc3c92049c32fbc553de2e5fb8e4');
$settings->setDeviceModel('PC 64bit');
$settings->setSystemVersion('4.14.186');
$settings->setAppVersion('1.28.5');
$settings->setSystemLangCode('en-US');
$settings->setLangPack('android');
$settings->setLangCode('en-US');


/* LiveProto Settings ( optional ) */
$settings->setHotReload(false);
$settings->setFloodSleepThreshold(120);
$settings->setReceiveUpdates(false);
$settings->setMaxConnections(10);
$settings->setMinConnections(5);
$settings->setParallelDownloads(50);
$settings->setParallelUploads(3);
$settings->setAutoCachePeers(false);
$settings->setIPv6(true);
$settings->setTestMode(false);
$settings->setDC(1);
$settings->setProtocol(Tak\Liveproto\Enums\ProtocolType::TcpFull);
$settings->setProxy(type : 'socks5',address : '127.0.0.1:443',username : 'ProxyUser',password : 'ProxyPassword');
$settings->setTakeout(message_users : true,message_chats : true,message_megagroups : true,message_channels : true);
$params = new \Tak\Liveproto\Tl\Types\Other\JsonObject([
	'value'=>array(
		new \Tak\Liveproto\Tl\Types\Other\JsonObjectValue([
			'key'=>'tz_offset',
			'value'=>new \Tak\Liveproto\Tl\Types\Other\JsonNumber([
				'value'=>(float) (new DateTime('now',new DateTimeZone(date_default_timezone_get())))->getOffset()
			])
		])
	)
]);
$settings->setParams($params);
$settings->setSaveTime(3);
$settings->setHideLog(false);
$settings->setMaxSizeLog(100 * 1024 * 1024);
$settings->setPathLog('Liveproto.log');


/* If you want to use MySQL */
$settings->setServer('localhost');
$settings->setUsername('Username');
$settings->setPassword('Password');
$settings->setDatabase('DatabaseName');
```

---

## API ID

- Type : `Integer` <kbd style="color : red">required</kbd>
- Default : `21724`

If this parameter is not set, the api id of an official client is used by default, The API ID you obtained from https://my.telegram.org

!> If your client is not [official](en/configuration.md#Params), using the **API ID** of an [official](en/configuration.md#Params) client is dangerous

## API HASH

- Type : `String` <kbd style="color : red">required</kbd>
- Default : `3e0cb5efcd52300aec5994fdfc5bdc16`

If this parameter is not set, the api hash of an official client is used by default, The API HASH you obtained from https://my.telegram.org

!> If your client is not [official](en/configuration.md#Params), using the **API HASH** of an [official](en/configuration.md#Params) client is dangerous

## Device Model

- Type : `String` <kbd>optional</kbd>
- Default : `$OperatingSystemName`

Defaults to php_uname('s')

## System Version

- Type : `String` <kbd>optional</kbd>
- Default : `$ReleaseName`

Defaults to php_uname('r')

## App Version

- Type : `String` <kbd>optional</kbd>
- Default : `0.26.8.1721-universal`

Default is an [official](en/configuration.md#Params) app version

## System Lang Code

- Type : `String` <kbd>optional</kbd>
- Default : `$Locale`

If the intl extension is enable, it will be used, otherwise the default value is 'en-US'

## Lang Pack

- Type : `String` <kbd>optional</kbd>
- Default : `android`

!> **If you want to use this feature, you must have an [official](en/configuration.md#Params) client !**

## Lang Code

- Type : `String` <kbd>optional</kbd>
- Default : `$Locale`

If the intl extension is enable, it will be used, otherwise the default value is 'en'

## Hot Reload

- Type : `Boolean` <kbd>optional</kbd>
- Default : `True`

If this parameter is set to false, The previous process creates a lock on the session, preventing it from being executed again

?> Note, If false, To be more clear, it executes one process, keeps one in the queue, and cancels subsequent executions OR If true, Every time you run the script, the previous process is disconnected and killed, and a new process is run with the new script and updated code

## Flood Sleep Threshold

- Type : `Integer` <kbd>optional</kbd>
- Default : `120`

If you encounter a FLOOD error, and if it is less than the set value, it will resend the request after X seconds, and if the flood wait time is more than the set time, you will get an error

## Receive Updates

- Type : `Boolean` <kbd>optional</kbd>
- Default : `True`

If this parameter is set to false,use invokeWithoutUpdates(query = initConnection), If you give false value, your session will no longer receive updates

## Max Connections

- Type : `Integer` <kbd>optional</kbd>
- Default : `10`

Specifies the maximum number of connections for the media

!> Increasing the number of connections only helps in cases where the client is trying to upload or download multiple files at the same time

## Min Connections

- Type : `Integer` <kbd>optional</kbd>
- Default : `5`

!> Try not to lower it too much as you may experience problems with upload speed and media upload

Minimum number of connections required for media connections

## Parallel Downloads

- Type : `Integer` <kbd>optional</kbd>
- Default : `50`

The number of requests sent simultaneously to download the file

!> Increasing it does not always mean increasing download speed and may cause problems

## Parallel Uploads

- Type : `Integer` <kbd>optional</kbd>
- Default : `3`

The number of requests sent simultaneously to upload the file

!> Increasing it may significantly increase resource consumption, but may also increase upload speed

## Auto Cache Peers

- Type : `Boolean` <kbd>optional</kbd>
- Default : `False`

If true, the client initially caches all peers each time so that there is no gap in finding peers

?> I personally recommend enabling this for userbots

!> For bots, the process may take a long time due to the large number of updates. First make sure then try to enable it

## IPv6

- Type : `Boolean` <kbd>optional</kbd>
- Default : `False`

If its value is false, it uses Telegram IP version 4 (ipv4), and if it is true, it uses Telegram IP version 6 (ipv6)

!> Note, You cannot change it later ! For the first time, everything you set is no longer changeable

## Takeout

- Type : `...Mixed` <kbd>optional</kbd>
- Default : `False`

You can pass anything that method [initTakeoutSession](https://tl.liveproto.dev/#/method/account.initTakeoutSession) accepts as a parameter

!> Note, You can also provide an empty array to enable it , like `$settings->setTakeout(array())`

## Test Mode

- Type : `Boolean` <kbd>optional</kbd>
- Default : `False`

If this parameter is set to true, you will be connected to [Telegram's test servers](en/testservers.md)

## DC

- Type : `Integer` <kbd>optional</kbd>
- Default : `0 | Random`

Enter the number of the data center you want to connect to

## Protocol

- Type : `ProtocolType` <kbd>optional</kbd>
- Default : `ProtocolType::FULL`

Set the tcp connection [`protocol`](en/enums.md#ProtocolType)

## Proxy

- Type : `...String` <kbd>optional</kbd>

`SOCKS5` and `HTTP` : You must pass `type` and `address` arguments & `username` and `password` parameters are optional

`SOCKS4` : You must pass `type` and `address` arguments & `user` parameter is optional

`MTPROXY` : You must pass `type` and `address` and `secret` arguments

!> Possible values ​​for the type parameter : `SOCKS5` , `SOCKS4` , `HTTP` , `MTPROXY`

?> **TLS** is set up by adding the letter `s` to the end of each proxy type , like ( case insensitive ) : `SOCKS5s` , `SOCKS4s` , `HTTPs`

```php
$settings->setProxy(type : 'socks4',address : '127.0.0.1:443',user : 'ProxyUser');

$settings->setProxy(url : 'socks5://<username>:<password>@127.0.0.1:443');

$settings->setProxy(type : 'mtproxy',url : 'https://t.me/proxy?server=127.0.0.1&port=443&secret=<secret>');

$settings->setProxy(type : 'https',address : '127.0.0.1:443');

$settings->setProxy(type : 'http',address : '127.0.0.1:80',username : 'ProxyUser',password : 'ProxyPassword');
```

## Params

- Type : `Object | JSONValue` <kbd>optional</kbd>
- Default : `Null`

This parameter is only used for official clients

## Save Time

- Type : `Integer | Float` <kbd>optional</kbd>
- Default : `3`

The value is in seconds and after this time your information is saved in the database

## Hide Log

- Type : `Boolean` <kbd>optional</kbd>
- Default : `False`

If its value is true, then no more logs will be printed

## Max Size Log

- Type : `Integer` <kbd>optional</kbd>
- Default : `10 * 1024 * 1024 | 10MB`

This parameter must be given in bytes, if the size of your log file reaches this value, it will be deleted automatically

## Path Log

- Type : `String` <kbd>optional</kbd>
- Default : `Liveproto.log`

You can set the log file path in which file the logs are saved

### Server

- Type : `Integer` <kbd>optional</kbd>
- Default : `localhost`

The IP of the server on which the database is to be set

### Username

- Type : `String` <kbd style="color : red">required</kbd>

Your database username

### Password

- Type : `String` <kbd style="color : red">required</kbd>

Your database password

### Database

- Type : `String` <kbd>optional</kbd>
- Default : `$Username`

Your database name, By default, the database username value is set for it