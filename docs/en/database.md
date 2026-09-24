# Database

?> Note, The session and information about the peers must be saved somewhere, so here we want to introduce various data saving modes

## MySQL

MySQL database is currently the most optimal way and I recommend it to you

```php
use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();

/* Telegram Settings */
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');

/* If you want to use MySQL */
$settings->setServer('localhost');
$settings->setUsername('Username');
$settings->setPassword('Password');
$settings->setDatabase('DatabaseName');

$client = new Client('YourSessionName','mysql',$settings);
```

## SQLite

SQLite database is easy to set up and testing

```php
use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();

/* Telegram Settings */
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');

$client = new Client('YourSessionName','sqlite',$settings);
```

## String

So in this case, your database is stored as a single string in a file with the extension `.session`, very efficient for a quick start

> [!TIP]
> You can give a file path for the session name here ( like : `./folder/file` )

```php
use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();

/* Telegram Settings */
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');

$client = new Client('YourSessionName','string',$settings);
```

## Text

After launching and logging in, you can get the entire session as a text string and use it instead of the session name and save it wherever you want

```php
use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();

/* Telegram Settings */
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');

$client = new Client('YourSessionName','string',$settings);

/*
 * Your client must be authenticated here
 */
 
if($client->isAuthorized()){

	/* For example, save this string anywhere you want */
	$textString = strval($client);

	/* Then grab the same textString from wherever you saved it and use it */
	$client = new Client($textString,'text',$settings);
}
```

## Memory

If you don't want your session to be stored anywhere other than RAM, this is the right option for you

!> Both session name and storage type **must be set to null**

?> I think this is only useful for [Telegram test servers](en/testservers.md)

```php
use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();

/* Telegram Settings */
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');

$client = new Client(null,null,$settings);
```

### Task List
- [x] Providing infrastructure for adding custom databases by the user
  - [ ] Add it to client settings
- [ ] Support for various databases
  - [ ] Postgresql
  - [x] SQLite3
  - [ ] MongoDB
  - [x] MySQL