<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Enums;

enum TransferKind : string {
	case FILE = 'file';
	case STREAM = 'stream';
	case CALLBACK = 'callback';
	case BROWSER = 'browser';
}

?>