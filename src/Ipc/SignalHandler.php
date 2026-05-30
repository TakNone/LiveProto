<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Ipc;

use Tak\Liveproto\Utils\Logging;

use Tak\Asyncio\Loop;

final class SignalHandler {
	private mixed $fp;

	public const STOP_SIGNALS = [
		SIGTSTP=>'Interactive Stop ( Ctrl+Z )',
		SIGINT=>'Interrupt ( Ctrl+C )',
		SIGQUIT=>'Quit ( Ctrl+\\ )',
		SIGTERM=>'Termination Request'
	];
	public const RESTART_SIGNALS = [
		SIGHUP=>'Hangup ( reload / restart request )'
	];

	public function __construct(string $name,protected readonly object $client){
		$this->fp = fopen(sys_get_temp_dir().DIRECTORY_SEPARATOR.urlencode($name).chr(95).md5($_SERVER['SCRIPT_FILENAME']),'c+');
		if(function_exists('cli_set_process_title')):
			cli_set_process_title(uniqid('LiveProto'));
		endif;
		$this->register();
	}
	public function tryLock() : null {
		if(flock($this->fp,LOCK_EX | LOCK_NB) === false and function_exists('posix_kill')):
			rewind($this->fp);
			$pid = intval(stream_get_contents($this->fp));
			if($pid !== 0 and posix_kill($pid,0)):
				@posix_kill($pid,SIGTERM);
			endif;
		endif;
		rewind($this->fp);
		ftruncate($this->fp,0);
		fwrite($this->fp,strval(getmypid()));
		fflush($this->fp);
		return null;
	}
	public function unlock() : void {
		$uri = stream_get_meta_data($this->fp)['uri'];
		fclose($this->fp);
		@unlink($uri);
	}
	public function register() : void {
		if(extension_loaded('pcntl')):
			foreach(self::STOP_SIGNALS as $signal => $reason):
				Loop::unreference(Loop::onSignal($signal,fn() : null => $this->stop($reason)));
			endforeach;
			foreach(self::RESTART_SIGNALS as $signal => $reason):
				Loop::unreference(Loop::onSignal($signal,fn() : null => $this->restart($reason)));
			endforeach;
		else:
			Logging::log('Signal Handler','Signal handling requires pcntl extension !',E_WARNING);
		endif;
	}
	public function stop(? string $reason = null) : void {
		if(is_null($reason) === false):
			error_log('Stop : '.$reason);
		endif;
		$this->client->stop();
		die('The process was stopped');
	}
	public function restart(? string $reason = null) : void {
		if(is_null($reason) === false):
			error_log('Restart : '.$reason);
		endif;
		$this->client->connect(reconnect : true);
	}
}

?>