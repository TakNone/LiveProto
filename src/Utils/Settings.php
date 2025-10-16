<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

final class Settings {
	protected array $data;

	public function __get(string $name) : mixed {
		$index = strtolower($name);
		$value = isset($this->data[$index]) ? $this->data[$index] : self::envGuess($index);
		switch($index):
			case 'apiid':
				if(is_int($value) and $value <= 0) throw new \Exception('In the Settings , a valid value for the API ID has not been set');
				is_int($value) || $value = 21724;
				break;
			case 'apihash':
				if(is_string($value) and strlen($value) !== 32) throw new \Exception('In the Settings , a valid value for the API HASH has not been set');
				is_string($value) || $value = '3e0cb5efcd52300aec5994fdfc5bdc16';
				break;
			case 'devicemodel':
				is_string($value) || $value = php_uname('s');
				break;
			case 'systemversion':
				is_string($value) || $value = php_uname('r');
				break;
			case 'appversion':
				is_string($value) || $value = '0.26.8.1721-universal';
				break;
			case 'systemlangcode':
				is_string($value) || $value = (extension_loaded('intl') ? locale_get_primary_language(locale_get_default()).'-'.locale_get_region(locale_get_default()) : 'en-US');
				break;
			case 'langpack':
				is_string($value) || $value = 'android';
				break;
			case 'langcode':
				is_string($value) || $value = (extension_loaded('intl') ? locale_get_primary_language(locale_get_default()) : 'en');
				break;
			case 'hotreload':
				is_bool($value) || $value = true;
				break;
			case 'floodsleepthreshold':
				is_int($value) || $value = 120;
				break;
			case 'receiveupdates':
				is_bool($value) || $value = true;
				break;
			case 'maxconnections':
				is_int($value) || $value = 10;
				$value = min(max($value,1),100);
				break;
			case 'minconnections':
				is_int($value) || $value = 5;
				$value = min(max($value,1),$this->maxConnections);
				break;
			case 'paralleldownloads':
				is_int($value) || $value = 50;
				$value = min(max($value,1),100);
				break;
			case 'paralleluploads':
				is_int($value) || $value = 3;
				$value = min(max($value,1),100);
				break;
			case 'autocachepeers':
				is_bool($value) || $value = false;
				break;
			case 'ipv6':
				is_bool($value) || $value = false;
				break;
			case 'takeout':
				is_array($value) || $value = false;
				break;
			case 'protocol':
				is_a($value,'Tak\\Liveproto\\Enums\\ProtocolType') || $value = null;
				break;
			case 'proxy':
				if(is_array($value) and array_is_list($value) === false):
					if(isset($value['url']) and is_string($value['url'])):
						$parsed = parse_url($value['url']);
						if(isset($parsed['scheme']) and isset($value['type']) === false):
							$value['type'] = $parsed['scheme'];
						endif;
						if(isset($parsed['host'],$parsed['port']) and isset($value['address']) === false):
							$value['address'] = $parsed['host'].chr(58).min($parsed['port'],65535);
						endif;
						if(isset($parsed['user']) and isset($value['username']) === false):
							$value['username'] = $value['user'] = $parsed['user'];
						endif;
						if(isset($parsed['pass']) and isset($value['password']) === false):
							$value['password'] = $parsed['pass'];
						endif;
						if(isset($parsed['query'])):
							parse_str($parsed['query'],$query);
							if(isset($query['server'],$query['port']) and isset($value['address']) === false):
								$value['address'] = $query['server'].chr(58).min(abs(intval($query['port'])),65535);
							endif;
							if(isset($query['secret']) and isset($value['secret']) === false):
								$value['secret'] = $query['secret'];
							endif;
						endif;
					endif;
					if(isset($value['type']) === false) throw new \Exception('The `type` parameter value must be set in the proxy');
					if(is_string($value['type']) === false) throw new \Exception('The value of the `type` parameter in the proxy must be of string type');
					if(isset($value['address']) === false) throw new \Exception('The `address` parameter value must be set in the proxy');
					if(is_string($value['address']) === false) throw new \Exception('The value of the `address` parameter in the proxy must be of string type');
					if(isset($value['username']) === false || is_string($value['username']) === false):
						$value['username'] = null;
					endif;
					if(isset($value['password']) === false || is_string($value['password']) === false):
						$value['password'] = null;
					endif;
					if(isset($value['user']) === false || is_string($value['user']) === false):
						$value['user'] = null;
					endif;
					if(isset($value['secret']) === false || is_string($value['secret']) === false):
						$value['secret'] = null;
					endif;
				else:
					$value = null;
				endif;
				break;
			case 'params':
				is_object($value) || $value = null;
				break;
		endswitch;
		switch($index):
			case 'testmode':
				is_bool($value) || $value = false;
				break;
			case 'dc':
				is_int($value) || $value = 0;
				$value = min(max($value,0),5);
				break;
			case 'savetime':
				is_numeric($value) || $value = 0x3;
				$value = min(max($value,1),60);
				break;
			case 'server':
				is_string($value) || $value = 'localhost';
				break;
			case 'username':
				is_string($value) || $value = (string) null;
				break;
			case 'password':
				is_string($value) || $value = (string) null;
				break;
			case 'database':
				is_string($value) || $value = $this->username;
				break;
		endswitch;
		return $value;
	}
	public function __set(string $name,mixed $value) : void {
		$this->data[strtolower($name)] = $value;
	}
	public function __unset(string $name) : void {
		unset($this->data[strtolower($name)]);
	}
	public function __isset(string $name) : bool {
		return is_null($this->$name) === false;
	}
	public function __call(string $method,array $arguments) : mixed {
		if(preg_match('~^set([a-z0-9]+)$~i',$method,$match)):
			$name = $match[true];
			$this->$name = (array_is_list($arguments) and count($arguments) === 1) ? $arguments[false] : $arguments;
			return $this;
		elseif(preg_match('~^get([a-z0-9]+)$~i',$method,$match)):
			$name = $match[true];
			return $this->$name;
		else:
			throw new \Exception('Call to undefined function '.$method.'()');
		endif;
	}
	public function __debugInfo() : array {
		return $this->data;
	}
	static public function envGuess(string $name,mixed $default = null) : mixed {
		$camel = Tools::snakeTocamel(strtolower($name));
		$snake = Tools::camelTosnake($name);
		$names = array($name,$camel,lcfirst($camel),$snake,strtoupper($snake));
		foreach($names as $name):
			$env = $_ENV[$name] ?? null;
			if(is_null($env) === false):
				return $env;
			endif;
			/* Env can be `true` / `false` / `integer` / `JSON` (array) */
			$env = getenv($name);
			if($env !== false):
				$json = json_decode(strval($env),true);
				return match(true){
					is_numeric($env) => intval($env),
					boolval(is_null($json) === false) => $json,
					default => $env
				};
			endif;
		endforeach;
		return $default;
	}
}

?>