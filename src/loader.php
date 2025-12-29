<?php

declare(strict_types = 1);

defined('CRLF') || define('CRLF',chr(13).chr(10));

defined('LFCR') || define('LFCR',chr(10).chr(13));

defined('SIGTSTP') || define('SIGTSTP',20);

defined('SIGINT') || define('SIGINT',2);

defined('SIGQUIT') || define('SIGQUIT',3);

defined('SIGTERM') || define('SIGTERM',15);

defined('SIGHUP') || define('SIGHUP',1);

class_alias(Tak\Liveproto\Network\Client::class,Tak\Liveproto\API::class);

class_alias(Tak\Liveproto\Filters\Filter::class,Tak\Liveproto\Handler::class);

class_alias(Tak\Liveproto\Errors\RpcError::class,Tak\Liveproto\Errors::class);

const REQUIRED_PHP_VERSION = '8.3.0';

const REQUIRED_EXTENSIONS = array(
	'openssl',
	'gmp',
	'json',
	'xml',
	'dom',
	'filter',
	'hash',
	'zlib',
	'fileinfo'
);

if(version_compare(PHP_VERSION,REQUIRED_PHP_VERSION,'>=') === false):
	throw new \LogicException('Minimum PHP version required : '.REQUIRED_PHP_VERSION);
endif;

if(PHP_INT_SIZE !== 8):
	throw new \LogicException('PHP must be 64-bit');
endif;

foreach(REQUIRED_EXTENSIONS as $extension):
	if(extension_loaded($extension) === false):
		throw new \LogicException('Extension '.$extension.' is not loaded');
	endif;
endforeach;

?>