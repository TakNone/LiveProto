<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Database;

use Revolt\EventLoop;

use ArrayAccess;

final class Content implements ArrayAccess {
	protected string $pending;
	protected bool $cloned;
	protected float $lastsave;
	protected Session $session;

	public function __construct(public array $data,protected float $savetime){
		$this->cloned = false;
		$this->lastsave = microtime(true);
		register_shutdown_function(fn() : null => $this->save(true));
	}
	public function setSession(Session $session) : self {
		$this->session = $session;
		return $this;
	}
	public function save(bool $urgent = false) : void {
		if(isset($this->session)):
			if($this->cloned === false):
				$sleep = $this->savetime - (microtime(true) - $this->lastsave);
				if($sleep <= 0 || $urgent):
					$this->lastsave = microtime(true);
					$this->pending = strval(null);
					$this->session->save();
				elseif(empty($this->pending)):
					$this->pending = EventLoop::unreference(EventLoop::delay($sleep,fn(string $id) : null => $this->save(true)));
				endif;
			endif;
		else:
			throw new \Exception('The session is not set up , I can not save new information !');
		endif;
	}
	public function &__get(string $property) : mixed {
		if(isset($this->data[$property]) === false):
			$this->data[$property] = null;
		endif;
		return $this->data[$property];
	}
	public function __set(string $name,mixed $value) : void {
		$this->data[$name] = $value;
		$this->save();
	}
	public function __unset(string $name) : void {
		unset($this->data[$name]);
		$this->save();
	}
	public function __isset(string $name) : bool {
		return isset($this->data[$name]);
	}
	public function &offsetGet(mixed $offset) : mixed {
		if(isset($this->data[$offset]) === false):
			$this->data[$offset] = null;
		endif;
		return $this->data[$offset];
	}
	public function offsetSet(mixed $offset,mixed $value) : void {
		$this->data[$offset] = $value;
		$this->save();
	}
	public function offsetUnset(mixed $offset) : void {
		unset($this->data[$offset]);
		$this->save();
	}
	public function offsetExists(mixed $offset) : bool {
		return isset($this->data[$offset]);
	}
	public function toArray() : array {
		return $this->data;
	}
	public function __debugInfo() : array {
		return $this->data;
	}
	public function __clone() : void {
		$this->cloned = true;
	}
	public function __serialize() : array {
		return array(
			'pending'=>strval(null),
			'cloned'=>$this->cloned,
			'savetime'=>$this->savetime,
			'lastsave'=>$this->lastsave,
			'data'=>$this->data
		);
	}
	public function __wakeup() : void {
		register_shutdown_function(fn() : null => $this->save(true));
	}
}

?>