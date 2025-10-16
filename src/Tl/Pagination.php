<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl;

use Tak\Liveproto\Utils\Tools;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Iterator;

use Closure;

use Throwable;

final class Pagination implements Iterator {
	protected ? Closure $computeLongHash;
	private int $hash = 0;
	private array $results = array();
	private int $position = 0;

	public function __construct(protected Closure $callback,public int $offset,public int $limit,Closure | array | null $hasher = null){
		if(is_array($hasher)):
			$this->computeLongHash = empty($hasher) ? null : fn(int $hash,array $results) : int => fn(int $hash,array $results) : int => Helper::hashGeneration($hash,Tools::populateIds($results,$hasher));
		else:
			$this->computeLongHash = $hasher;
		endif;
	}
	public function current() : mixed {
		return $this->results[$this->position];
	}
	public function key() : mixed {
		return $this->position;
	}
	public function next() : void {
		$this->position++;
	}
	public function rewind() : void {
		$this->position = 0;
	}
	public function exists() : bool {
		return isset($this->results[$this->position]);
	}
	public function valid() : bool {
		if($this->exists() === false):
			try {
				Logging::log('Pagination','offset = '.$this->offset.' & limit = '.$this->limit.' & hash = '.$this->hash,0);
				$results = call_user_func($this->callback,$this->offset,abs($this->limit),$this->hash);
				if(empty($results) === false):
					$this->results = array_merge($this->results,$results);
					$this->offset += $this->limit;
					if(is_callable($this->computeLongHash)):
						$this->hash = call_user_func($this->computeLongHash,$this->hash,$results);
					endif;
				endif;
			} catch(Throwable $error){
				Logging::log('Pagination',$error->getMessage(),E_NOTICE);
			}
		endif;
		return $this->exists();
	}
	public function __debugInfo() : array {
		return array(
			'hash'=>$this->hash,
			'position'=>$this->position
		);
	}
}

?>