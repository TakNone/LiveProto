<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Crypto\Obfuscation;

use Tak\Liveproto\Network\Proxy\Socks5SocketConnector;

use Tak\Liveproto\Network\Proxy\Socks4SocketConnector;

use Tak\Liveproto\Network\Proxy\HttpSocketConnector;

use Amp\Socket\ConnectContext;

use Amp\TimeoutCancellation;

use function Amp\Socket\connect;

final class TcpClient {
	private ConnectContext $context;
	private object $socket;
	public bool $connected;

	public function __construct(float $timeout = 10,? int $dns = null,bool $nodelay = false){
		$context = new ConnectContext();
		$context = $context->withConnectTimeout($timeout);
		$context = $context->withDnsTypeRestriction($dns);
		$context = ($nodelay ? $context->withTcpNoDelay() : $context->withoutTcpNoDelay());
		$this->context = $context;
		$this->connected = false;
	}
	public function connect(string $ip,int $port,? array $proxy = null) : void {
		if(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)):
			$uri = sprintf('tcp://[%s]:%d',$ip,$port);
		elseif(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)):
			$uri = sprintf('tcp://%s:%d',$ip,$port);
		else:
			throw new \InvalidArgumentException('Invalid IP !');
		endif;
		if(is_null($proxy)):
			$this->socket = connect($uri,$this->context);
		else:
			if(preg_match('~^socks5(?<tls>s|(?:\+|\-)?tls)?~i',$proxy['type'],$match)):
				$socks5 = new Socks5SocketConnector(proxyAddress : $proxy['address'],username : $proxy['username'],password : $proxy['password']);
				$this->socket = $socks5->connect(uri : $uri,context : $this->context,secure : isset($match['tls']));
			elseif(preg_match('~^socks4(?<tls>s|(?:\+|\-)?tls)?~i',$proxy['type'],$match)):
				$socks4 = new Socks4SocketConnector(proxyAddress : $proxy['address'],user : $proxy['user']);
				$this->socket = $socks4->connect(uri : $uri,context : $this->context,secure : isset($match['tls']));
			elseif(preg_match('~^http(?<tls>s)?~i',$proxy['type'],$match)):
				$http = new HttpSocketConnector(proxyAddress : $proxy['address'],username : $proxy['username'],password : $proxy['password']);
				$this->socket = $http->connect(uri : $uri,context : $this->context,secure : isset($match['tls']));
			elseif(strtoupper($proxy['type']) === 'MTPROXY'):
				$uri = sprintf('tcp://%s',$proxy['address']);
				$this->socket = connect($uri,$this->context);
				if(isset($proxy['secret']) and Obfuscation::emulateTls(Obfuscation::fromLink($proxy['secret']))):
					$tls = new TlsHandshake(target : $uri,proxy : $proxy);
					$this->socket = $tls->exchange($this->socket);
				endif;
			else:
				throw new \OutOfRangeException('Proxy type '.$proxy['type'].' is out of supported range : socks4 , socks5 , http , mtproxy');
			endif;
		endif;
		$this->socket->setChunkSize(PHP_INT_MAX);
		$this->connected = true;
	}
	public function close() : void {
		if($this->connected):
			$this->connected = false;
			$this->socket->close();
		endif;
	}
	public function write(string $data) : void {
		if($this->socket->isClosed()):
			throw new \RuntimeException('The connection was completely closed !');
		elseif($this->connected):
			$this->socket->write($data);
		else:
			throw new \RuntimeException('First you need to connect to the server !');
		endif;
	}
	public function read(int $size,int $timeout = 60) : string {
		$result = (string) null;
		$cancellation = $timeout > 0 ? new TimeoutCancellation($timeout) : null;
		while($size > strlen($result)):
			if($this->socket->isClosed()):
				throw new \RuntimeException('The connection was completely closed !');
			elseif($this->connected):
				$buffer = $this->socket->read($cancellation,$size - strlen($result));
				if(is_null($buffer) === false):
					$result .= $buffer;
				else:
					$this->close();
					throw new \RuntimeException('Connection closed by remote host ( EOF ) !');
				endif;
			else:
				throw new \RuntimeException('First you need to connect to the server !');
			endif;
		endwhile;
		return $result;
	}
	public function __destruct(){
		$this->close();
	}
}

?>