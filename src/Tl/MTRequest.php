<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl;

use Tak\Liveproto\Utils\Binary;

use Tak\Liveproto\Utils\Instance;

use Tak\Liveproto\Utils\Logging;

use Amp\DeferredFuture;

use Stringable;

/*
 * A client must never mark msgs_ack, msg_container, msg_copy, gzip_packed constructors (i.e. containers and acknowledgements) as content-related, or else a bad_msg_notification with error_code=34 will be emitted
 * [contentRelated = false]
 * [sequence % 2 === 1]
 */

class MTRequest implements Stringable {
	public ? Instance $tl = null;
	public ? Binary $binary = null;
	private ? DeferredFuture $deferred = null;

	public function __construct(string | Instance | Binary $tl,public ? int $messageId = null,public mixed $identifier = null,public ? int $sequence = null,public bool $contentRelated = true,public bool $keepOldSalt = false,public float $timeout = 0,public ? object $cancellation = null){
		if(is_string($tl)):
			list($what,$space,$name) = array_pad(array_map(ucfirst(...),explode(chr(46),$tl,3)),-3,null);
			if(in_array($space,['Functions','Types','Cores'])):
				$what = $space;
				$space = null;
			endif;
			$space = is_null($space) ? 'Other' : $space;
			$whats = is_null($what) ? ['Functions','Types','Cores'] : array($what);
			foreach($whats as $what):
				if($tl = $this->createObject('Tak\\Liveproto\\Tl\\'.$what.'\\'.$space.'\\'.$name)):
					break;
				endif;
			endforeach;
		endif;
		if($tl instanceof Binary):
			$this->binary = $tl;
		elseif($tl instanceof Instance):
			$this->tl = $tl;
		else:
			throw new \LogicException('The tl you requested was not found');
		endif;
	}
	public function withParameters(mixed ...$arguments) : self {
		if(is_null($this->tl) === false):
			$this->tl = new $this->tl($arguments);
			$this->binary = $this->tl->stream();
			return $this;
		else:
			throw new \InvalidArgumentException('This request does not assign a new parameter');
		endif;
	}
	public function getBinary(bool $renew = false) : Binary {
		$this->binary = ($renew || is_null($this->binary)) ? $this->tl->stream() : $this->binary;
		return $this->binary;
	}
	public function getConstructor() : Instance {
		$constructorId = $this->getBinary()->readInt();
		$this->getBinary()->undo();
		return All::getConstructor($constructorId);
	}
	public function hash() : string {
		return md5(spl_object_hash($this->getBinary()));
	}
	public function createObject(string $class) : object | false {
		return class_exists($class) ? new $class : false;
	}
	public function toMessage(int $msg_id,int $seqno) : string {
		# message msg_id:long seqno:int bytes:int body:Object = Message; #
		$message = new Binary();
		$this->messageId ??= $msg_id;
		$message->writeLong($this->messageId);
		$this->sequence ??= $seqno;
		$message->writeInt($this->sequence);
		$packet = $this->getBinary()->read();
		$packetLength = strlen($packet);
		# gzip_packed#3072cfa1 packed_data:string = Object; #
		if($packetLength > 512 and boolval($this->sequence % 2 === 1)):
			$gzip = new Binary();
			$gzip->writeInt(0x3072cfa1);
			$gzip->writeBytes(gzencode($packet));
			$compressed = $gzip->read();
			$compressedLength = strlen($compressed);
			if($packetLength > $compressedLength):
				Logging::log('Gzip','compressed size : '.$compressedLength.' , using compressed payload ( saves '.intval($packetLength - $compressedLength).' bytes )');
				$message->writeInt($compressedLength);
				$message->write($compressed);
				return $message->read();
			endif;
		endif;
		$message->writeInt($packetLength);
		$message->write($packet);
		return $message->read();
	}
	static public function fromMessage(Binary $message) : self {
		# message msg_id:long seqno:int bytes:int body:Object = Message; #
		$msg_id = $message->readLong();
		$seqno = $message->readInt();
		$packetLength = $message->readInt();
		$packet = $message->read($packetLength);
		$body = new Binary();
		$body->write($packet);
		return new self($body,messageId : $msg_id,sequence : $seqno);
	}
	public function getDeferred(bool $lazyInit = true) : ? DeferredFuture {
		$this->deferred = boolval($lazyInit and is_null($this->deferred)) ? new DeferredFuture : $this->deferred;
		return $this->deferred;
	}
	public function __clone() : void {
		$this->messageId = null;
		$this->sequence = null;
		$this->deferred = null;
	}
	public function __toString() : string {
		return md5(spl_object_hash($this->getBinary()));
	}
}

?>