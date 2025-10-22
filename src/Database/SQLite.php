<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Database;

use Tak\Liveproto\Utils\Tools;

use Tak\Liveproto\Utils\Logging;

use Revolt\EventLoop;

use Amp\Sync\LocalMutex;

use PDO;

final class SQLite implements AbstractDB , AbstractPeers {
	protected object $connection;

	public function __construct(string $path){
		$this->connection = new PDO('sqlite:'.$path);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	public function init(string $table) : bool {
		$stmt = $this->connection->query('SELECT name FROM sqlite_master WHERE type = \'table\' AND name = '.chr(39).$table.chr(39));
		if($stmt->fetch(PDO::FETCH_ASSOC)):
			return false;
		else:
			$this->connection->exec(
				'CREATE TABLE IF NOT EXISTS '.$table.' (
				`id` BIGINT NOT NULL DEFAULT 0
				)'
			);
			$this->connection->prepare('INSERT OR IGNORE INTO '.$table.' (`id`) VALUES (:id)')->execute(['id'=>0]);
			return true;
		endif;
	}
	public function set(string $table,string $key,mixed $value,string $type) : void {
		static $mutex = new LocalMutex;
		$lock = $mutex->acquire();
		try {
			if($this->exists($table,$key) === false):
				$this->connection->exec('ALTER TABLE '.$table.' ADD COLUMN '.$key.chr(32).$type);
			endif;
			$this->connection->prepare('UPDATE '.$table.' SET '.$key.' = :new')->execute(['new'=>$value]);
		} catch(\Throwable $error){
			Logging::log('SQLite',$error->getMessage(),E_WARNING);
		} finally {
			EventLoop::queue($lock->release(...));
		}
	}
	public function get(string $table) : array | null {
		$stmt = $this->connection->query('SELECT * FROM '.$table);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row === false ? null : $row;
	}
	public function delete(string $table,string $key) : void {
		$this->connection->exec('UPDATE '.$table.' SET `'.$key.'` = NULL');
	}
	public function exists(string $table,string $key) : bool {
		$columnsStmt = $this->connection->prepare('PRAGMA table_info('.$table.')');
		$columnsStmt->execute();
		$columns = $columnsStmt->fetchAll(PDO::FETCH_ASSOC);
		$fields = array_column($columns,'name');
		return in_array($key,$fields);
	}
	public function initPeer(string $table) : bool {
		$stmt = $this->connection->query('SELECT name FROM sqlite_master WHERE type = \'table\' AND name = '.chr(39).$table.chr(39));
		if($stmt->fetch(PDO::FETCH_ASSOC)):
			return false;
		else:
			$this->connection->exec(
				'CREATE TABLE IF NOT EXISTS '.$table.' (
				`id` BIGINT PRIMARY KEY
				)'
			);
			return true;
		endif;
	}
	public function setPeer(string $table,mixed $value) : void {
		static $mutex = new LocalMutex;
		$lock = $mutex->acquire();
		try {
			$keys = array_keys($value);
			$values = array_values($value);
			$columnsStmt = $this->connection->prepare('PRAGMA table_info('.$table.')');
			$columnsStmt->execute();
			$columns = $columnsStmt->fetchAll(PDO::FETCH_ASSOC);
			$fields = array_column($columns,'name');
			foreach($value as $k => $v):
				if(in_array($k,$fields) === false):
					$type = Tools::inferType($v);
					$this->connection->exec('ALTER TABLE '.$table.' ADD COLUMN `'.$k.'` '.$type);
				endif;
			endforeach;
			$this->connection->prepare(
				implode(chr(32),array(
					'INSERT INTO '.$table,
					'(`'.implode(chr(96).chr(44).chr(96),$keys).'`)',
					'VALUES',
					'('.chr(58).implode(chr(44).chr(58),$keys).')',
					'ON CONFLICT(`id`) DO UPDATE SET',
					implode(chr(44),array_map(fn(string $key) : string => strval($key.' = excluded.'.$key),$keys))
				))
			)->execute($value);
		} catch(\Throwable $error){
			Logging::log('SQLite',$error->getMessage(),E_WARNING);
		} finally {
			EventLoop::queue($lock->release(...));
		}
	}
	public function getPeer(string $table,string $key,mixed $value) : array | null {
		$stmt = $this->connection->prepare('SELECT * FROM '.$table.' WHERE '.$key.' = :value');
		$stmt->execute(['value'=>$value]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row === false ? null : $row;
	}
	public function deletePeer(string $table,string $key,mixed $value) : void {
		$this->connection->prepare('DELETE FROM '.$table.' WHERE '.$key.' = :value')->execute(['value'=>$value]);
	}
}

?>