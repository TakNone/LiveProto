# Quick start

?> Note, Before starting, it is better to familiarize yourself with the methods of [installing](en/installation.md) the library, then try the following codes !

---

## Creating a Client

Here we will explain how to create a <mark>LiveProto Client</mark> instance

```php
<?php

if(file_exists('liveproto.php') === false):
	copy('https://installer.liveproto.dev/liveproto.php','liveproto.php');
endif;

require_once 'liveproto.php';

use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Utils\Settings;

$settings = new Settings();
$settings->setApiId(5368212);
$settings->setApiHash('87fb8a7a6dc103f87e2e27a30868ce86');

$client = new Client('YourSessionName','string',$settings);
```

<details open>
<summary style="color : lightskyblue">new Client(string | null $resourceName,string | null $storageDriver,object $settings)</summary>

#### ResourceName

- Type : `String | Null` <kbd style="color : red">required</kbd>

In this parameter, you enter the name of your session

!> When you give **Null** value, your session information is not saved anywhere and it is only useful for using Telegram's test servers !

#### StorageDriver

- Type : `String | Null` <kbd style="color : red">required</kbd>

In this parameter, you specify how your session information will be stored

!> You can give **Null** value only when [ResourceName](en/quickstart.md#ResourceName) value is also **Null**

#### Settings

- Type : `Object` <kbd style="color : red">required</kbd>

In this object you specify exactly what configuration you want to do for your session

?> Note, Be aware that this object of yours must be an instance of [`Tak\Liveproto\Utils\Settings`](en/configuration.md#Settings)

</details>

> [Click here](en/database.md) to learn more about session databases

> [Click here](en/configuration.md) to configure settings