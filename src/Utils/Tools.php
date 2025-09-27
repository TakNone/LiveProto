<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

use function Amp\ByteStream\getStdin;

use function Amp\ByteStream\getStdout;

abstract class Tools {
	static public function readLine(? string $prompt = null,? object $cancellation = null) : string {
		try {
			$stdin = getStdin();
			$stdout = getStdout();
			if(is_null($prompt) === false) $stdout->write($prompt);
			static $lines = array(null);
			while(count($lines) < 2 and ($chunk = $stdin->read($cancellation)) !== null):
				$chunk = explode(chr(10),str_replace(array(chr(13),chr(10).chr(10)),chr(10),$chunk));
				$lines[count($lines) - 1] .= array_shift($chunk);
				$lines = array_merge($lines,$chunk);
			 endwhile;
		} catch(\Throwable $error){
			Logging::log('Tools',$error->getMessage(),E_WARNING);
		}
		return strval(array_shift($lines));
	}
	static public function snakeTocamel(string $str) : string {
		return str_replace('_',(string) null,ucwords($str,'_'));
	}
	static public function camelTosnake(string $str) : string {
		return strtolower(preg_replace('/([a-z])([A-Z])/','$1_$2',$str));
	}
	static public function isCli() : bool {
		return in_array(PHP_SAPI,['cli','cli-server','phpdbg','embed']);
	}
	static public function marshal(array $data) : array {
		foreach($data as $key => $value):
			if(is_object($value) || is_array($value) || is_bool($value) || mb_check_encoding(var_export($value,true),'UTF-8') === false):
				$data[$key] = 'serialize:'.base64_encode(serialize($value));
			elseif(is_string($value) and str_starts_with($value,'serialize:')):
				$serialize = substr($value,10);
				$data[$key] = unserialize(base64_decode($serialize));
			endif;
		endforeach;
		return $data;
	}
	static public function inferType(mixed $data) : string {
		$type = match(gettype($data)){
			'boolean' => 'BOOLEAN',
			'object' , 'array' => 'LONGTEXT',
			'integer' => 'BIGINT', // abs($data) > 0x7fffffff ? 'BIGINT' : 'INT' //
			'double' => 'REAL',
			'string' => 'TEXT', // mb_strlen($data) > 0xffff ? 'LONGTEXT' : 'TEXT' //
			default => 'VARCHAR ('.mb_strlen($data).')'
		};
		return $type;
	}
	static public function is_valid_mysql_identifier_unicode(string $name) : bool {
		/*
		 * Unicode-aware MySQL validation :
		 * Allows Unicode letters / digits ( using \p{L}\p{N} ), underscore and dollar sign
		 * MySQL allows leading digit but identifier cannot be all digits
		 * Max length 64 codepoints ( approx, MySQL stores identifiers in UTF-8, most deployments treat 64 characters )
		 */
		return boolval(preg_match('/^(?!^\d+$)[\p{L}\p{N}_\$]{1,64}$/u',$name));
	}
	static public function is_valid_sqlite_identifier_unicode(string $name) : bool {
		/*
		 * Unicode-aware SQLite validation ( unquoted-name style ):
		 * Start with a Unicode letter or underscore
		 * Then Unicode letters / digits / underscore / dollar
		 * Max length 64
		 */
		return boolval(preg_match('/^[\p{L}_][\p{L}\p{N}_\$]{0,63}$/u',$name));
	}
	static public function base64_url_encode(string $string) : string {
		return rtrim(strtr(base64_encode($string),chr(43).chr(47),chr(45).chr(95)),chr(61));
	}
	static public function base64_url_decode(string $string) : string | false {
		return base64_decode(strtr($string,chr(45).chr(95),chr(43).chr(47)));
	}
	static function inDestructor(? array $stack = null) : bool {
		$stack ??= debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach($stack as $frame):
			if(isset($frame['function']) and $frame['function'] === '__destruct'):
				return true;
			endif;
		endforeach;
		return false;
	}
	static public function populateIds(array $results,array $indexes) : array {
		$ids = array();
		foreach($results as $result):
			foreach($indexes as $index):
				if(is_array($index) === false) $index = array($index);
				$values = array_filter(array_map(fn(string | int | array $property) : mixed => self::getNested($result,$property),$index));
				$ids []= empty($values) ? 0 : reset($values);
			endforeach;
		endforeach;
		return $ids;
	}
	static public function getNested(mixed $result,mixed $property) : mixed {
		if(is_string($property)):
			$result = isset($result->$property) ? $result->$property : null;
		elseif(is_int($property)):
			$result = isset($result[$property]) ? $result[$property] : null;
		elseif(is_array($property)):
			foreach($property as $i):
				$result = self::getNested($result,$i);
			endforeach;
		endif;
		return $result;
	}
}

?>