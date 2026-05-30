<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network\Proxy;

use Tak\Asyncio\Socket\TlsContext;

use Tak\Asyncio\Socket\StreamSocket;

final class HttpSocketConnector {
	private StreamSocket $socket;

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
		if(is_null($username) or is_null($password)):
			$authHeader = strval(null);
		else:
			$authHeader = 'Proxy-Authorization: Basic '.base64_encode($username.':'.$password).CRLF;
		endif;
		$ip = inet_pton($host);
		if($ip !== false and strlen($ip) === 16):
			$host = sprintf('[%s]',$ip);
		endif;
		$socket->write('CONNECT '.$host.':'.$port.' HTTP/1.1'.CRLF.'Host: '.$host.':'.$port.CRLF.'Accept: */*'.CRLF.$authHeader.'Connection: keep-Alive'.CRLF.CRLF);
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
		$headers = strval(null);
		do {
			$piece = $read(2);
			$headers .= $piece;
			if($piece === LFCR):
				$headers .= $read(1);
				break;
			elseif(str_ends_with($headers,CRLF.CRLF)):
				break;
			endif;
		} while(true);
		$headers = explode(CRLF,$headers);
		list($protocol,$code,$description) = explode(chr(32),array_shift($headers),3);
		list($protocol,$version) = explode(chr(47),$protocol);
		if($protocol !== 'HTTP'):
			throw new \RuntimeException('Wrong protocol : '.$protocol);
		elseif(array_pop($headers).array_pop($headers) !== strval(null)):
			throw new \RuntimeException('Wrong last HTTP header');
		elseif($code != 200):
			throw new \RuntimeException($code.chr(32).$description,intval($code));
		endif;
		$headers = array_change_key_case(array_column(array_map(fn(string $item) : array => array_map('trim',explode(chr(58),$item,2)),$headers),1,0));
		if(isset($headers['content-length'])):
			$length = intval($headers['content-length']);
			if($length > 0):
				$content = $read($length);
			endif;
		endif;
	}
}

?>