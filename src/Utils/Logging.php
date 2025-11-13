<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

use Revolt\EventLoop;

use function Amp\File\openFile;

use function Amp\File\getSize;

use function Amp\File\deleteFile;

use function Amp\File\exists;

final class Logging {
	private const COLORS = [
		E_ERROR=>['message'=>'Error','settings'=>['fg'=>'bright_cyan','bg'=>'red','options'=>['bold']]],
		E_WARNING=>['message'=>'Warning','settings'=>['fg'=>'bright_magenta','bg'=>'yellow','options'=>['blink']]],
		E_PARSE=>['message'=>'Parse','settings'=>['fg'=>'bright_red','bg'=>'green','options'=>['underline']]],
		E_NOTICE=>['message'=>'Notice','settings'=>['fg'=>'bright_yellow','bg'=>'black']],
		E_ALL=>['message'=>'Info','settings'=>['fg'=>'cyan','bg'=>'black']]
	];

	static public string $path = '.'.DIRECTORY_SEPARATOR.'Liveproto.log';
	static public int $maxsize = 0xa00000;
	static public bool $hide = false;

	public function __construct(Settings $settings){
		if(is_string($settings->pathLog)) self::$path = $settings->pathLog;
		if(is_int($settings->maxSizeLog)) self::$maxsize = $settings->maxSizeLog;
		if(is_bool($settings->hideLog)) self::$hide = $settings->hideLog;
	}
	static public function log(string $name,mixed $text,int $level = E_ALL) : void {
		static $log = null;
		if(boolval(error_reporting() & $level) === false) return;
		if(array_key_exists($level,self::COLORS)):
			$message = self::COLORS[$level]['message'];
			$settings = self::COLORS[$level]['settings'];
		else:
			throw new \InvalidArgumentException('The log level is invalid or not supported');
		endif;
		if(Tools::inDestructor() === false and self::$maxsize > 0):
			if(exists(self::$path) and getSize(self::$path) >= self::$maxsize):
				try {
					deleteFile(self::$path);
				} catch(\Throwable $error){
					error_log('Error while deleting previous log : '.$error->getMessage());
				} finally {
					$log = null;
				}
			endif;
			if(exists(self::$path) === false or is_null($log)):
				$log = openFile(self::$path,'a+');
			endif;
			$backtrace = debug_backtrace();
			$log->write(str_pad($name.' ( '.$message.' ) ',0x20,chr(0x20),STR_PAD_RIGHT).'[ '.date('Y/m/d H:i:s').' ]'.' : '.print_r($text,true).' on line '.$backtrace[false]['line'].PHP_EOL);
		endif;
		if(self::$hide === false):
			Tools::colorize(str_pad($message,10,chr(0x20),STR_PAD_RIGHT).' : '.print_r($text,true),...$settings);
		endif;
	}
}

?>