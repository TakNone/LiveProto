# Installation

You can install the library and use it in two ways, the first way is to use Phar File and the second way is to use Composer

---

## Phar

If you want to have a quick and easy start, it is recommended to use this method, you can download library in the form of a single file as `liveproto.php`

?> Note, You can also use a file to automatically download the latest phar file for you and even update it automatically (`auto-update`) and include it

```php
<?php

if(file_exists('liveproto.php') === false):
    copy('https://installer.liveproto.dev/liveproto.php','liveproto.php');
endif;

require_once 'liveproto.php';
```

?> Note, By changing `latest` to the version you want, you can use any version for example :

```php
<?php

define('LP_VERSION','0.0.15');

if(file_exists('liveproto.php') === false):
    copy('https://installer.liveproto.dev/liveproto.php','liveproto.php');
endif;

require_once 'liveproto.php';
```

OR

```php
<?php

if(file_exists('liveproto-v0.0.15.phar') === false):
    copy('https://phar.liveproto.dev/v0.0.15/liveproto.phar','liveproto-v0.0.15.phar');
endif;

require_once 'liveproto-v0.0.15.phar';
```

---

## Composer

You can also use the following command line to install the library

> _This is the best way_

```bash
composer require taknone/liveproto
```

!> **Composer v2+ is required !**

And finally, follow the code below to use the library

```php
<?php

require_once 'vendor/autoload.php';
```

---

### Composer from scratch 

> `composer.json` file content :

```json
{
    "require": {
        "taknone/liveproto": "*"
    },
    "config": {
        "allow-plugins": {
            "taknone/bootstrapper": true
        }
    }
}
```

> Then run this command line

```bash
composer update
```

---

## Docker Based

Install Docker and verify `docker --version`

> Pull the image

```bash
docker pull taknone/liveproto:latest
```

> Try an interactive shell & Run example

```bash
docker run --rm -it taknone/liveproto:latest /bin/sh

php /app/examples/bot-example.php
```

---

## Bash Installer

Typically used to perform a local install, fetch dependencies, or run helper setup commands provided by the project maintainers

> Run the remote install script

```bash
bash <(curl -Ls https://raw.githubusercontent.com/TakNone/LiveProto/master/install.sh)
```