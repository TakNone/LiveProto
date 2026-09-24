<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

use Tak\Liveproto\Parser\Tl;

use Stringable;

use JsonSerializable;

use ArrayAccess;

abstract class Instance implements Stringable , JsonSerializable , ArrayAccess {
	protected object $client;
	protected array $sdtClass;

	public function __construct(array $dictionary = array()){
		$this->sdtClass = $dictionary;
	}
	public function &__get(string $name) : mixed {
		if(isset($this->sdtClass[$name]) === false):
			$this->sdtClass[$name] = null;
		endif;
		return $this->sdtClass[$name];
	}
	public function __set(string $name,mixed $value) : void {
		$this->sdtClass[$name] = $value;
	}
	public function __unset(string $name) : void {
		unset($this->sdtClass[$name]);
	}
	public function __isset(string $name) : bool {
		return isset($this->sdtClass[$name]);
	}
	public function __call(string $name,array $arguments) : mixed {
		if($this->$name and is_callable($this->$name)):
			return call_user_func($this->$name,...$arguments);
		elseif(isset($this->client)):
			return call_user_func(array($this->client,$name),...$arguments);
		else:
			throw new \Exception('Call to undefined function '.$name.'()');
		endif;
	}
	public function __invoke(mixed ...$arguments) : mixed {
		if(method_exists($this,'request') and method_exists($this,'response')):
			if(count($arguments) === 1):
				if(array_key_exists('reader',$arguments)):
					if($arguments['reader'] instanceof Binary):
						return $this->response(...$arguments);
					endif;
				endif;
			endif;
			return $this->request(...$arguments);
		else:
			throw new \Exception('The object does not have a request and response method !');
		endif;
	}
	public function stream(bool $resolveKeys = false) : Binary {
		if($resolveKeys):
			$comments = Tl::parseDocComment($this);
			$params = array_keys($comments['param']);
			$arguments = array_intersect_key($this->sdtClass,array_flip(array_map(fn(string $param) : string => strtok($param,chr(36)),$params)));
			return $this->request(...$arguments);
		else:
			return $this->request(...$this->sdtClass);
		endif;
	}
	public function read() : string {
		return $this->stream()->read();
	}
	public function write(Binary $writer) : Binary {
		$writer->write($this->read());
		return $writer;
	}
	public function __sleep() : array {
		return array('sdtClass');
	}
	public function __debugInfo() : array {
		return array_filter($this->sdtClass,fn(mixed $value) : bool => is_a($value,'\Closure') === false);
	}
	static public function __set_state(array $arrays) : object {
		return new static($arrays['sdtClass']);
	}
	public function __toString() : string {
		return $this->dumpTree();
	}
	public function &offsetGet(mixed $offset) : mixed {
		if(isset($this->sdtClass[$offset]) === false):
			$this->sdtClass[$offset] = null;
		endif;
		return $this->sdtClass[$offset];
	}
	public function offsetSet(mixed $offset,mixed $value) : void {
		$this->sdtClass[$offset] = $value;
	}
	public function offsetUnset(mixed $offset) : void {
		unset($this->sdtClass[$offset]);
	}
	public function offsetExists(mixed $offset) : bool {
		return isset($this->sdtClass[$offset]);
	}
	public function toArray() : array {
		$class = ['_'=>$this->getClass()];
		return array_merge($class,$this->sdtClass);
	}
	public function jsonSerialize() : array {
		return $this->toArray();
	}
	public function getClass() : string {
		$split = explode(chr(92),get_class($this));
		$name = lcfirst(array_pop($split));
		$space = lcfirst(array_pop($split));
		return strval($space === 'other' ? $name : $space.chr(46).$name);
	}
	public function clone(string | object $object,bool $deep = false) : object {
		$cloned = new $object($this->sdtClass);
		if($cloned instanceof self):
			$cloned = $cloned->setClient($this->getClient());
			if($deep):
				$cloned = $cloned->stream(true)->readObject();
			endif;
		endif;
		return $cloned;
	}
	public function setClient(object | null $client) : self {
		if(is_null($client)):
			unset($this->client);
		else:
			$this->client = $client;
		endif;
		return $this;
	}
	public function getClient() : object | null {
		return isset($this->client) ? $this->client : null;
	}
	private function dumpTree(array $array = array(),int $depth = 0) : string {
		$string = strval(null);
		$array = empty($array) ? $this->sdtClass : $array;
		$newline = (Tools::isCli() ? chr(10) : (ob_get_level() > 0 ? PHP_EOL : '<br>'));
		if($depth === 0) $string .= $newline.'┌';
		foreach($array as $key => $value):
			$string .= $newline.($depth > 0 ? str_repeat('│'.chr(9),$depth) : null).'├ '.$key.(' ► '.($this->stringifyType($value) === 'array' ? call_user_func(__METHOD__,$value,$depth + 1) : $this->stringifyType($value)));
		endforeach;
		if($depth === 0) $string .= $newline.'└';
		return $string;
	}
	private function stringifyType(mixed $type) : string {
		return match(gettype($type)){
			'boolean' => $type ? 'true' : 'false',
			'integer' => strval($type),
			'double' => strval($type),
			'object' => $type::class,
			'NULL' => 'null',
			'array' => 'array',
			'string' => $type,
			default => 'unknown'
		};
	}
}

?>