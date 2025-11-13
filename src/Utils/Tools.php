<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

use function Amp\ByteStream\getStdin;

use function Amp\ByteStream\getStdout;

defined('STDOUT') || define('STDOUT',fopen('php://stdout','wb'));

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
	static public function colorize(mixed $text,? string $fg = null,? string $bg = null,array $options = []) : void {
		if(is_string($text) === false):
			$text = var_export($text,true);
		endif;
		$fgMap = [
			'black'=>30,
			'red'=>31,
			'green'=>32,
			'yellow'=>33,
			'blue'=>34,
			'magenta'=>35,
			'cyan'=>36,
			'white'=>37,
			'reset'=>39,
			'bright_black'=>90,
			'bright_red'=>91,
			'bright_green'=>92,
			'bright_yellow'=>93,
			'bright_blue'=>94,
			'bright_magenta'=>95,
			'bright_cyan'=>96,
			'bright_white'=>97
		];
		$bgMap = [
			'black'=>40,
			'red'=>41,
			'green'=>42,
			'yellow'=>43,
			'blue'=>44,
			'magenta'=>45,
			'cyan'=>46,
			'white'=>47,
			'reset'=>49,
			'bright_black'=>100,
			'bright_red'=>101,
			'bright_green'=>102,
			'bright_yellow'=>103,
			'bright_blue'=>104,
			'bright_magenta'=>105,
			'bright_cyan'=>106,
			'bright_white'=>107
		];
		$optsMap = [
			'reset'=>0,
			'bold'=>1,
			'dim'=>2,
			'underline'=>4,
			'blink'=>5,
			'reverse'=>7,
			'hidden'=>8,
		];
		$cssColorMap = [
			'black'=>'black',
			'red'=>'#d11',
			'green'=>'#0a0',
			'yellow'=>'#d90',
			'blue'=>'#06c',
			'magenta'=>'#c0f',
			'cyan'=>'#0af',
			'white'=>'#c0c0c0',
			'bright_black'=>'#666',
			'bright_red'=>'#ff6f6f',
			'bright_green'=>'#6f6',
			'bright_yellow'=>'#ffd86f',
			'bright_blue'=>'#6fb3ff',
			'bright_magenta'=>'#ff9cff',
			'bright_cyan'=>'#7ff',
			'bright_white'=>'#fff'
		];
		if(self::isCli()):
			if(self::cli_supports_ansi()):
				$codes = array();
				if(empty($fg) === false and array_key_exists($fg,$fgMap)):
					$codes []= $fgMap[$fg];
				endif;
				if(empty($bg) === false and array_key_exists($bg,$bgMap)):
					$codes []= $bgMap[$bg];
				endif;
				foreach($options as $opt):
					if(array_key_exists($opt,$optsMap)):
						$codes []= $optsMap[$opt];
					endif;
				endforeach;
				if(empty($codes)):
					print($text);
				else:
					printf(chr(27).'[%sm%s'.chr(27).'[0m'.PHP_EOL,implode(chr(59),$codes),$text);
				endif;
			else:
				print($text);
			endif;
		else:
			$escaped = htmlspecialchars($text,ENT_QUOTES | ENT_SUBSTITUTE,'UTF-8');
			$styles = array();
			if(in_array('bold',$options,true)):
				$styles []= 'font-weight: 700';
			endif;
			if(in_array('dim',$options,true)):
				$styles []= 'opacity: 0.75';
			endif;
			if(in_array('underline',$options,true)):
				$styles []= 'text-decoration: underline';
			endif;
			if(in_array('blink',$options,true)):
				$styles []= 'text-decoration: blink';
			endif;
			if(in_array('reverse',$options,true)):
				list($fg,$bg) = array($bg,$fg);
			endif;
			if(in_array('hidden',$options,true)):
				$styles []= 'visibility: hidden';
			endif;
			if(empty($fg) === false and array_key_exists($fg,$cssColorMap)):
				$styles []= 'color: '.$cssColorMap[$fg];
			endif;
			if(empty($bg) === false and array_key_exists($bg,$cssBgMap)):
				$styles []= 'background-color: '.$cssColorMap[$bg];
			endif;
			$styleAttr = strval(empty($styles) ? null : ' style = '.chr(34).implode(chr(59).chr(32),$styles).chr(34));
			if(str_contains($escaped,chr(10))):
				print('<pre'.$styleAttr.'>'.$escaped.'</pre>');
			else:
				print('<span'.$styleAttr.'>'.$escaped.'</span><br>');
			endif;
		endif;
	}
	static public function cli_supports_ansi() : bool {
		if(function_exists('stream_isatty')):
			if(@stream_isatty(STDOUT) === false) return false;
		endif;
		if(DIRECTORY_SEPARATOR === chr(92)):
			if(function_exists('sapi_windows_vt100_support')):
				@sapi_windows_vt100_support(STDOUT,true);
			endif;
		endif;
		return true;
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