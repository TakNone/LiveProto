<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network\Proxy;

use Tak\Asyncio\Socket\TlsContext;

use Tak\Asyncio\Socket\StreamSocket;

final class Socks5SocketConnector {
	private StreamSocket $socket;

	private const REPLIES = [
		0 => 'Succeeded',
		1 => 'General SOCKS server failure',
		2 => 'Connection not allowed by ruleset',
		3 => 'Network unreachable',
		4 => 'Host unreachable',
		5 => 'Connection refused',
		6 => 'TTL expired',
		7 => 'Command not supported',
		8 => 'Address type not supported'
	];
	
	public function __construct(private readonly string $proxyAddress,private readonly ? string $username = null,private readonly ? string $password = null){
		$this->socket = new StreamSocket(new Uri($proxyAddress)->domain);
	}
	public function connect(string $host,int $port,TlsContext $context = new TlsContext,bool $secure = false,int $timeout = -1) : StreamSocket {
		$uri = new Uri($this->proxyAddress);
		if($this->socket->connect($uri->address,$uri->port,$timeout) === false):
			throw new \Error('Connection to proxy failed');
		endif;
		self::tunnel($this->socket,$host,$port,$this->username,$this->password,$timeout);
		if($secure):
			$this->socket->setupTls($context);
		endif;
		return $this->socket;
	}
	static public function tunnel(StreamSocket $socket,string $host,int $port,? string $username,? string $password,int $timeout = -1) : void {
		if(is_null($username) !== is_null($password)):
			throw new \RuntimeException('Both or neither username and password must be provided !');
		endif;
		$methods = chr(0);
		if(isset($username) and isset($password)) $methods .= chr(2);
		$socket->write(chr(5).chr(strlen($methods)).$methods);
		$read = function(int $length) use($socket,$timeout) : string {
			assert($length > 0);
			$buffer = strval(null);
			do {
				$limit = $length - strlen($buffer);
				assert($limit > 0);
				$chunk = $socket->read($limit,$timeout);
				if($chunk === null):
					throw new \RuntimeException('The socket was closed before the tunnel could be established');
				endif;
				$buffer .= $chunk;
			} while(strlen($buffer) !== $length);
			return $buffer;
		};
		$version = ord($read(1));
		if($version !== 5):
			throw new \RuntimeException('Wrong SOCKS5 version : '.$version);
		endif;
		$method = ord($read(1));
		if($method === 2):
			if(is_null($username) or is_null($password)):
				throw new \RuntimeException('Unexpected method : '.$method);
			endif;
			$socket->write(chr(1).chr(strlen($username)).$username.chr(strlen($password)).$password);
			$version = ord($read(1));
			if($version !== 1):
				throw new \RuntimeException('Wrong authorized SOCKS version : '.$version);
			endif;
			$result = ord($read(1));
			if($result !== 0):
				throw new \RuntimeException('Wrong authorization status : '.$result);
			endif;
		elseif($method !== 0):
			throw new \RuntimeException('Unexpected method : '.$method);
		endif;
		$ip = inet_pton($host);
		$payload = pack('C3',0x5,0x1,0x0);
		if($ip !== false):
			$payload .= chr(strlen($ip) === 4 ?  0x1 : 0x4).$ip;
		else:
			$payload .= chr(0x3).chr(strlen($host)).$host;
		endif;
		$payload .= pack('n',$port);
		$socket->write($payload);
		$version = ord($read(1));
		if($version !== 5):
			throw new \RuntimeException('Wrong SOCKS5 version : '.$version);
		endif;
		$reply = ord($read(1));
		if($reply !== 0):
			$reply = self::REPLIES[$reply] ?? $reply;
			throw new \RuntimeException('Wrong SOCKS5 reply : '.$reply);
		endif;
		$rsv = ord($read(1));
		if($rsv !== 0):
			throw new \RuntimeException('Wrong SOCKS5 RSV : '.$rsv);
		endif;
		$read(match(ord($read(1))){
			0x1 => 4 + 2,
			0x4 => 16 + 2,
			0x3 => ord($read(1)) + 2
		});
	}
}

?>