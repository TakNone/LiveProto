<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Database;

use Tak\Liveproto\Enums\Authentication;

use Tak\Liveproto\Utils\Settings;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Tools;

use Amp\Mysql\MysqlConfig;

use function Amp\File\isFile;

use function Amp\File\read;

use function Amp\File\write;

use function Amp\File\getSize;

final class Session {
	protected Content $content;
	protected MySQL $mysql;
	protected SQLite $sqlite;
	private array $servers = array(
		['ip'=>'149.154.175.50','ipv6'=>'2001:b28:f23d:f001:0000:0000:0000:000a','port'=>443,'dc'=>1],
		['ip'=>'149.154.167.51','ipv6'=>'2001:67c:4e8:f002:0000:0000:0000:000a','port'=>443,'dc'=>2],
		['ip'=>'149.154.175.100','ipv6'=>'2001:b28:f23d:f003:0000:0000:0000:000a','port'=>443,'dc'=>3],
		['ip'=>'149.154.167.91','ipv6'=>'2001:67c:4e8:f004:0000:0000:0000:000a','port'=>443,'dc'=>4],
		['ip'=>'91.108.56.180','ipv6'=>'2001:b28:f23f:f005:0000:0000:0000:000a','port'=>443,'dc'=>5]
	);
	private array $testservers = array(
		['ip'=>'149.154.175.40','ipv6'=>'2001:b28:f23d:f001:0000:0000:0000:000e','port'=>80,'dc'=>1],
		['ip'=>'149.154.167.40','ipv6'=>'2001:67c:4e8:f002:0000:0000:0000:000e','port'=>80,'dc'=>2],
		['ip'=>'149.154.175.117','ipv6'=>'2001:b28:f23d:f003:0000:0000:0000:000e','port'=>80,'dc'=>3]
	);
	private string $mode;

	public function __construct(public readonly string | null $name,? string $mode,public readonly Settings $settings){
		$this->mode = strtolower(strval($mode));
	}
	public function generate() : object {
		if($this->dc > 0):
			if($this->testmode):
				if($this->dc <= count($this->testservers)):
					$server = $this->testservers[$this->dc - 1];
				else:
					throw new \Exception('The ID of the test data center must be less than '.count($this->testservers));
				endif;
			else:
				if($this->dc <= count($this->servers)):
					$server = $this->servers[$this->dc - 1];
				else:
					throw new \Exception('The ID of the data center must be less than '.count($this->servers));
				endif;
			endif;
		else:
			if($this->testmode):
				$server = $this->testservers[array_rand($this->testservers)];
			else:
				$server = $this->servers[array_rand($this->servers)];
			endif;
		endif;
		return new Content([
			'id'=>0,
			'api_id'=>0,
			'api_hash'=>(string) null,
			'dc'=>$server['dc'],
			'ip'=>($this->ipv6 ? $server['ipv6'] : $server['ip']),
			'port'=>$server['port'],
			'auth_key'=>new \stdClass,
			'expires_at'=>0,
			'salt'=>0,
			'salt_valid_until'=>0,
			'sequence'=>0,
			'time_offset'=>0,
			'last_msg_id'=>0,
			'logout_tokens'=>array(),
			'peers'=>new CachedPeers($this->name),
			'state'=>(object) array('pts'=>1,'qts'=>-1,'date'=>1,'seq'=>0),
			'step'=>Authentication::NEED_AUTHENTICATION
		],$this->savetime);
	}
	public function load() : object {
		if(isset($this->content)):
			return $this->content;
		elseif(is_null($this->name)):
			$this->content = $this->generate();
			$this->content->setSession($this);
			return $this->content;
		else:
			switch($this->mode):
				case 'string':
					$file = '.'.DIRECTORY_SEPARATOR.$this->name.'.session';
					if(isFile($file) and getSize($file)):
						$this->content = unserialize(gzinflate(base64_decode(read($file))))->setSession($this);
					else:
						$content = $this->generate();
						write($file,base64_encode(gzdeflate(serialize($content))));
						$this->content = $content->setSession($this);
					endif;
					break;
				case 'sqlite':
					Tools::is_valid_sqlite_identifier_unicode($this->name) || throw new \InvalidArgumentException('resourceName : must start with a letter / _ and contain only Unicode letters / digits / _ / $ ( 1..64 chars ) !');
					$path = empty($this->database) ? $this->name : $this->database;
					$this->sqlite = new SQLite($path.'.db');
					if($this->sqlite->init($this->name)):
						$content = $this->generate();
					else:
						$content = new Content(Tools::marshal($this->sqlite->get($this->name)),$this->savetime);
					endif;
					$this->content = $content->setSession($this);
					$this->content->peers->init($this->sqlite);
					$this->content->save(true);
					break;
				case 'mysql':
					Tools::is_valid_mysql_identifier_unicode($this->name) || throw new \InvalidArgumentException('resourceName : must be 1..64 Unicode letters / digits / _ / $ and not composed only of digits !');
					if(empty($this->server)):
						throw new \Exception('Server parameter for mysql database is empty !');
					elseif(empty($this->username)):
						throw new \Exception('Username parameter for mysql database is empty !');
					elseif(empty($this->password)):
						throw new \Exception('Password parameter for mysql database is empty !');
					elseif(empty($this->database)):
						throw new \Exception('Database parameter for mysql database is empty !');
					else:
						$config = MysqlConfig::fromAuthority($this->server,$this->username,$this->password,$this->database);
						$this->mysql = new MySQL($config);
						if($this->mysql->init($this->name)):
							$content = $this->generate();
						else:
							$content = new Content(Tools::marshal($this->mysql->get($this->name)),$this->savetime);
						endif;
						$this->content = $content->setSession($this);
						$this->content->peers->init($this->mysql);
						$this->content->save(true);
					endif;
					break;
				case 'text':
					$this->content = unserialize(gzinflate(base64_decode($this->name)))->setSession($this);
					break;
				default:
					throw new \InvalidArgumentException('The database mode is invalid ! It must be one of the modes of string , sqlite , mysql , text !');
			endswitch;
		endif;
		return $this->content;
	}
	public function save() : void {
		if(is_null($this->name) === false):
			switch($this->mode):
				case 'string':
					$file = '.'.DIRECTORY_SEPARATOR.$this->name.'.session';
					if(isFile($file)):
						write($file,base64_encode(gzdeflate(serialize($this->content))));
					endif;
					break;
				case 'sqlite':
					$data = Tools::marshal($this->content->toArray());
					array_walk($data,fn(mixed $value,string $key) : mixed => $this->sqlite->set($this->name,$key,$value,Tools::inferType($value)));
					break;
				case 'mysql':
					$data = Tools::marshal($this->content->toArray());
					array_walk($data,fn(mixed $value,string $key) : mixed => $this->mysql->set($this->name,$key,$value,Tools::inferType($value)));
					break;
				case 'text':
					/*
					 * New information in the text session is not stored anywhere
					 * You may encounter warnings in some cases
					 * I do not recommend this type of session
					 */
					break;
				default:
					throw new \InvalidArgumentException('The database mode is invalid ! It must be one of the modes of string , sqlite , mysql , text !');
			endswitch;
		endif;
	}
	public function reset(? int $id = null) : void {
		$this->content['id'] = is_null($id) ? Helper::generateRandomLong() : $id;
		$this->content['sequence'] = 0;
		$this->content['last_msg_id'] = 0;
		gc_collect_cycles();
	}
	public function getServerTime() : int {
		return intval(time() + $this->content['time_offset']);
	}
	public function updateTimeOffset(int $msgId) : void {
		$old = $this->content['time_offset'];
		$sec = intval($msgId >> 32);
		$this->content['time_offset'] = $sec - time();
		if($new !== $old):
			$this->content['last_msg_id'] = 0;
		endif;
	}
	public function getNewMsgId() : int {
		$now = $this->getServerTime();
		$msgId = intval($now << 32);
		$msgId = $msgId & (~ 0x3);
		$this->content['last_msg_id'] = $msgId = boolval($msgId <= $this->content['last_msg_id']) ? intval($this->content['last_msg_id'] + 4) : $msgId;
		return $msgId;
	}
	public function generateSequence(bool $contentRelated = true) : int {
		if($contentRelated):
			$seqno = $this->content['sequence'] * 2 + 1;
			$this->content['sequence'] += 1;
			return $seqno;
		else:
			return $this->content['sequence'] * 2;
		endif;
	}
	public function getStringSession() : string {
		$session = clone $this;
		$content = $session->load();
		return base64_encode(gzdeflate(serialize($content)));
	}
	public function __get(string $property) : mixed {
		return $this->settings->$property;
	}
	public function __set(string $property,mixed $value) : void {
		$this->settings->$property = $value;
	}
	public function __unset(string $property) : void {
		unset($this->settings->$property);
	}
	public function __isset(string $property) : bool {
		return isset($this->settings->$property);
	}
	public function __debugInfo() : array {
		return array(
			'content'=>isset($this->content) ? $this->content : null,
			'ipv6'=>$this->ipv6,
			'testmode'=>$this->testmode,
			'dc'=>$this->dc,
			'savetime'=>$this->savetime,
			'server'=>$this->server,
			'username'=>$this->username,
			'password'=>$this->password,
			'database'=>$this->database
		);
	}
	public function __clone() : void {
		$this->content = clone $this->content;
		$this->reset(id : 0);
	}
	public function __sleep() : array {
		return array('settings');
	}
}

?>