<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Crypto\Obfuscation;

use Tak\Liveproto\Network\Proxy\Socks5SocketConnector;

use Tak\Liveproto\Network\Proxy\Socks4SocketConnector;

use Tak\Liveproto\Network\Proxy\HttpSocketConnector;

use Tak\Asyncio\Socket\StreamSocket;

final class TcpClient {
	private TlsSocket | StreamSocket $socket;
	public bool $connected {
		get => boolval($this->socket->isClosed() === false);
	}

	public function __construct(int $domain){
		$this->socket = new StreamSocket($domain);
	}
	public function connect(string $ip,int $port,? array $proxy = null) : void {
		if(is_null($proxy)):
			$this->socket->connect(host : $ip,port : $port,timeout : 60);
		else:
			if(preg_match('~^socks5(?<tls>s|(?:\+|\-)?tls)?~i',$proxy['type'],$match)):
				$socks5 = new Socks5SocketConnector(proxyAddress : $proxy['address'],username : $proxy['username'],password : $proxy['password']);
				$this->socket = $socks5->connect(host : $ip,port : $port,secure : isset($match['tls']),timeout : 60);
			elseif(preg_match('~^socks4(?<tls>s|(?:\+|\-)?tls)?~i',$proxy['type'],$match)):
				$socks4 = new Socks4SocketConnector(proxyAddress : $proxy['address'],user : $proxy['user']);
				$this->socket = $socks4->connect(host : $ip,port : $port,secure : isset($match['tls']),timeout : 60);
			elseif(preg_match('~^http(?<tls>s)?~i',$proxy['type'],$match)):
				$http = new HttpSocketConnector(proxyAddress : $proxy['address'],username : $proxy['username'],password : $proxy['password']);
				$this->socket = $http->connect(host : $ip,port : $port,secure : isset($match['tls']),timeout : 60);
			elseif(strtoupper($proxy['type']) === 'MTPROXY'):
				for($useLegacy = false , $retry = 3; $retry >= 0; $retry--):
					try {
						$uri = new Uri($proxy['address']);
						$this->socket = new StreamSocket($uri->domain);
						$this->socket->connect(host : $uri->address,port : $uri->port,timeout : 60);
						if(isset($proxy['secret']) and Obfuscation::emulateTls(Obfuscation::fromLink($proxy['secret']))):
							$tls = new TlsHandshake(proxy : $proxy);
							$this->socket = $tls->exchange(socket : $this->socket,useLegacy : $useLegacy);
						endif;
						break;
					} catch(\Throwable $error){
						$useLegacy = boolval($useLegacy === false);
						if($retry === 0) throw $error;
					}
				endfor;
			else:
				throw new \OutOfRangeException('Proxy type '.$proxy['type'].' is out of supported range : socks4 , socks5 , http , mtproxy');
			endif;
		endif;
	}
	public function close() : bool {
		return $this->socket->close();
	}
	public function write(string $data,int $timeout = 60) : void {
		if($this->connected):
			$this->socket->write($data,$timeout > 0 ? $timeout : -1);
		else:
			throw new \RuntimeException('The connection was completely closed !');
		endif;
	}
	public function read(int $size,int $timeout = 60) : string {
		$result = strval(null);
		while($size > strlen($result)):
			if($this->connected):
				$buffer = $this->socket->read($size - strlen($result),$timeout > 0 ? $timeout : -1);
				if(is_string($buffer)):
					$result .= $buffer;
				else:
					$this->close();
					throw new \RuntimeException('Connection closed by remote host ( EOF ) !');
				endif;
			else:
				throw new \RuntimeException('The connection was completely closed !');
			endif;
		endwhile;
		return $result;
	}
	public function __destruct(){
		$this->close();
	}
}

?>