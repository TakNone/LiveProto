<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Database;

use Tak\Liveproto\Utils\Tools;

use Tak\Liveproto\Utils\Logging;

use Tak\Asyncio\Loop;

use Tak\Asyncio\Sync\Mutex;

use PDO;

final class MySQL implements AbstractDB , AbstractPeers {
	protected object $connection;

	public function __construct(string $server,string $username,string $password,string $database){
		$this->connection = new PDO('mysql:host='.$server.'; dbname='.$database.'; charset=utf8mb4',$username,$password);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
	}
	public function init(string $table) : bool {
		if($this->connection->query('SHOW TABLES LIKE '.chr(39).$table.chr(39))->fetch() and $this->connection->query('SELECT * FROM '.$table.' LIMIT 1')->fetch()){
			return false;
		} else {
			$this->connection->exec('CREATE TABLE IF NOT EXISTS '.$table.' (`id` BIGINT NOT NULL DEFAULT 0) DEFAULT CHARSET=utf8mb4');
			$this->connection->prepare('INSERT IGNORE INTO '.$table.' (`id`) VALUES (:id)')->execute(['id'=>0]);
			return true;
		}
	}
	public function set(string $table,string $key,mixed $value,string $type) : void {
		static $mutex = new Mutex;
		$lock = $mutex->acquire();
		try {
			if($this->exists($table,$key) === false){
				$this->connection->exec('ALTER TABLE '.$table.' ADD COLUMN IF NOT EXISTS '.$key.chr(32).$type);
			}
			$this->connection->prepare('UPDATE '.$table.' SET '.$key.' = :new')->execute(['new'=>$value]);
		} catch(\Throwable $error){
			Logging::log('MySQL',$error->getMessage(),E_WARNING);
		} finally {
			Loop::queue($lock->release(...));
		}
	}
	public function get(string $table) : array | null {
		$stmt = $this->connection->query('SELECT * FROM '.$table.' LIMIT 1');
		if($datas = $stmt->fetch()){
			return $this->castTypes($table,$datas);
		} else {
			return null;
		}
	}
	public function delete(string $table,string $key) : void {
		$this->connection->exec('ALTER TABLE '.$table.' DROP COLUMN '.$key);
	}
	public function exists(string $table,string $key) : bool {
		$stmt = $this->connection->query('DESCRIBE '.$table);
		$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
		return in_array($key,$columns);
	}
	public function initPeer(string $table) : bool {
		if($this->connection->query('SHOW TABLES LIKE '.chr(39).$table.chr(39))->fetch()){
			return false;
		} else {
			$this->connection->exec('CREATE TABLE IF NOT EXISTS '.$table.' (`id` BIGINT PRIMARY KEY) DEFAULT CHARSET=utf8mb4');
			return true;
		}
	}
	public function setPeer(string $table,mixed $value) : void {
		static $mutex = new Mutex;
		$lock = $mutex->acquire();
		try {
			$keys = array_keys($value);
			foreach($value as $key=>$val){
				$type = Tools::inferType($val);
				$this->connection->exec('ALTER TABLE '.$table.' ADD COLUMN IF NOT EXISTS `'.$key.'` '.$type);
			}
			$cols = implode(chr(96).chr(44).chr(96),$keys);
			$placeholders = chr(58).implode(chr(44).chr(58),$keys);
			$update = implode(chr(44),array_map(fn($k) => $k.' = VALUES('.$k.')',$keys));
			$this->connection->prepare('INSERT INTO '.$table.' (`'.$cols.'`) VALUES ('.$placeholders.') ON DUPLICATE KEY UPDATE '.$update)->execute($value);
		} catch(\Throwable $error){
			Logging::log('MySQL',$error->getMessage(),E_WARNING);
		} finally {
			Loop::queue($lock->release(...));
		}
	}
	public function getPeer(string $table,string $key,mixed $value) : array | null {
		$stmt = $this->connection->prepare('SELECT * FROM '.$table.' WHERE '.$key.' = :value');
		$stmt->execute(['value'=>$value]);
		if($datas = $stmt->fetch()){
			return $this->castTypes($table,$datas);
		} else {
			return null;
		}
	}
	public function deletePeer(string $table,string $key,mixed $value) : void {
		$this->connection->prepare('DELETE FROM '.$table.' WHERE '.$key.' = :value')->execute(['value'=>$value]);
	}
	private function castTypes(string $table,array $datas) : array {
		$stmt = $this->connection->query('SHOW COLUMNS FROM '.$table);
		while($column = $stmt->fetch()){
			$field = strval($column['Field'] ?? null);
			$type = strval($column['Type'] ?? null);
			if(array_key_exists($field,$datas)){
				$datas[$field] = Tools::specifyType($type,$datas[$field]);
			}
		}
		return $datas;
	}
}

?>