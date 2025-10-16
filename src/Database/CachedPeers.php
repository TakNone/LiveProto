<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Database;

use Tak\Liveproto\Utils\Tools;

final class CachedPeers {
	private array $datas;
	protected ? object $connection = null;

	public function __construct(private readonly ? string $name){
		$this->datas = [
			'users'=>array(),
			'chats'=>array(),
			'secrets'=>array()
		];
	}
	public function init(object $connection) : void {
		if(is_null($this->name) === false and is_subclass_of($connection,AbstractPeers::class)):
			foreach(array_keys($this->datas) as $key):
				$connection->initPeer($this->name.chr(95).strtoupper($key));
			endforeach;
			$this->connection = $connection;
		endif;
	}
	public function getPeer(string $type,string $by,mixed $what) : array | null {
		$filtered = array_filter($this->datas[$type],fn(array $row) : bool => $row[$by] === $what);
		if($found = reset($filtered)):
			return $found;
		elseif(isset($this->connection)):
			$found = $this->connection->getPeer($this->name.chr(95).strtoupper($type),$by,$what);
			return is_null($found) ? $found : Tools::marshal($found);
		else:
			return null;
		endif;
	}
	public function setPeers(string $type,array $peers) : void {
		if(isset($this->connection)):
			foreach($peers as $peer):
				$this->connection->setPeer($this->name.chr(95).strtoupper($type),Tools::marshal($peer));
			endforeach;
		else:
			$this->datas[$type] = array_unique(array_merge($peers,$this->datas[$type]),SORT_REGULAR);
		endif;
	}
	public function deletePeer(string $type,string $by,mixed $what) : void {
		if(isset($this->connection)):
			$this->connection->deletePeer($this->name.chr(95).strtoupper($type),$by,$what);
		else:
			$this->datas[$type] = array_filter($this->datas[$type],fn(array $row) : bool => $row[$by] !== $what);
		endif;
	}
	public function __debugInfo() : array {
		return array('peers'=>$this->datas);
	}
	public function __sleep() : array {
		return array('name','datas');
	}
}

?>