<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network\Proxy;

use Tak\Asyncio\Socket\TlsContext;

use Tak\Asyncio\Socket\StreamSocket;

final class Socks4SocketConnector {
	private StreamSocket $socket;

	private const REPLIES = [
		0x5a => 'Succeeded',
		0x5b => 'Request rejected or failed',
		0x5c => 'Request failed because client is not running identd',
		0x5d => 'Request different user id'
	];

	public function __construct(private readonly string $proxyAddress,private readonly ? string $user = null){
		$this->socket = new StreamSocket(new Uri($proxyAddress)->domain);
	}
	public function connect(string $host,int $port,TlsContext $context = new TlsContext,bool $secure = false,int $timeout = -1) : StreamSocket {
		$uri = new Uri($this->proxyAddress);
		if($this->socket->connect($uri->address,$uri->port,$timeout) === false):
			throw new \Error('Connection to proxy failed');
		endif;
		self::tunnel($this->socket,$host,$port,$this->user,$timeout);
		if($secure):
			$this->socket->setupTls($context);
		endif;
		return $this->socket;
	}
	static public function tunnel(StreamSocket $socket,string $host,int $port,? string $user,int $timeout = -1) : void {
		$ip = inet_pton($host);
		/*
		 * If host is an IPv4 address we will use SOCKS4. Otherwise , for domain names use SOCKS4a
		 * For SOCKS4a we must send 0.0.0.1 as DSTIP and append domain after USERID NUL
		 */
		$payload = pack('C2',0x4,0x1);
		$payload .= pack('n',$port);
		if(extension_loaded('iconv')):
			$user = @iconv('UTF-8','ASCII//TRANSLIT',strval($user));
		endif;
		if($ip !== false and strlen($ip) === 4):
			$payload .= $ip;
			$payload .= strval($user);
			$payload .= chr(0);
		else:
			$payload .= inet_pton('0.0.0.1');
			$payload .= strval($user);
			$payload .= chr(0);
			if(function_exists('idn_to_ascii')):
				$host = idn_to_ascii($host,IDNA_DEFAULT,INTL_IDNA_VARIANT_UTS46) ?: $host;
			endif;
			$payload .= $host;
			$payload .= chr(0);
		endif;
		$socket->write($payload);
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
		if($version !== 0):
			throw new \RuntimeException('Wrong SOCKS4 version : '.$version);
		endif;
		$reply = ord($read(1));
		if($reply !== 0x5a):
			$reply = self::REPLIES[$reply] ?? $reply;
			throw new \RuntimeException('Wrong SOCKS4 reply : '.$reply);
		endif;
		$read(2);
		$read(4);
	}
}

?>