# Test Servers

You can run LiveProto against Telegram's test servers

!> **Warning** : Test servers are public and meant for development and experimentation only. Do not store secrets, production API keys, or sensitive data in sessions you use against test servers

---

## What this does

Connecting to Telegram's test servers lets you exercise authentication and basic API flows without using real phone numbers or production data. The test server uses special phone number and login-code conventions so you can automate sign-in in tests

---

## Test server addresses

A commonly used test IP in Telegram docs is `149.154.167.40` listening on port `80`, prefer port `80` when trying the test server

---

## Test phone numbers and login codes

Valid test phone numbers follow the pattern `99966XYYYY` where `X` is the `dc_id` and `YYYY` is any number you choose. For example, for `dc_id = 2` you can use `9996621234`

The login code sent by Telegram to these test numbers is predictable in the test environment : it is the `dc_id` digit repeated multiple times ( historically five times, if five does not work, try repeating six times ) For `dc_id = 2` the code would be `22222` ( or possibly `222222` )

---

## Example

> Below are example snippets showing how you might connect LiveProto to a Telegram test server, The library exposes flexible session and connection handling, the exact API surface may vary by version, the snippets show two approaches ( set DC ID on the settings vs set IP and PORT on the client )

```php
<?php

if(file_exists('liveptoto.php') === false):
	copy('https://installer.liveproto.dev/liveproto.php','liveptoto.php');
endif;

require_once 'liveptoto.php';

use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();

/* Telegram Settings */
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');
$settings->setTestMode(true);
$settings->setDC(2);

$client = new Client(null,null,$settings);

/*
 * OR you can do it directly :
 * $client->setDC(id : 2,ip : '149.154.167.40',port : 443);
 */

$client->connect();

var_dump($client->send_code('+9996621235'));

var_dump($client->sign_in(22222));

$client->disconnect();

?>
```

---

## Safety & cleanup

Because test servers are public and designed for development :

* Avoid storing sensitive data in test sessions
* Use a dedicated session name ( for example `test_session` ) so you don't accidentally reuse a production session
* Delete or reset the test session file after your tests finish if you want a clean state
* You can even save the session to [short-term RAM storage](en/database.md#Memory)

---

## Troubleshooting

* If the predictable login code ( e.g. `22222` ) does not work, try repeating the `dc_id` six times ( e.g. `222222` )
* If your client cannot connect to the test IP, verify local network rules or firewall settings. Some hosts block non-443 outbound traffic