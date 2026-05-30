<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Enums\ProtocolType;

final class TcpTransport {
	public object $tcpClient;
	public readonly object $protocol;

	public function __construct(string $ip,int $port,int $dc_id,? ProtocolType $protocol = null,? array $proxy = null,bool $test_mode = false,bool $media_only = false){
		$this->tcpClient = new TcpClient(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4) ? AF_INET : AF_INET6);
		$this->tcpClient->connect($ip,$port,$proxy);
		if(is_null($proxy) === false and strtoupper($proxy['type']) === 'MTPROXY'):
			$protocol = ProtocolType::OBFUSCATED;
		else:
			$protocol = is_null($protocol) ? ProtocolType::FULL : $protocol;
		endif;
		$class = strval('\\Tak\\Liveproto\\Network\\Protocols\\'.$protocol->value);
		$this->protocol = match(true){
			$protocol === ProtocolType::ABRIDGED => new $class(tcpClient : $this->tcpClient),
			$protocol === ProtocolType::INTERMEDIATE => new $class(tcpClient : $this->tcpClient),
			$protocol === ProtocolType::PADDEDINTERMEDIATE => new $class(tcpClient : $this->tcpClient),
			$protocol === ProtocolType::OBFUSCATED => new $class(tcpClient : $this->tcpClient,dc_id : $dc_id,test_mode : $test_mode,media_only : $media_only,secret : (is_null($proxy) ? null : $proxy['secret'])),
			$protocol === ProtocolType::HTTP => new $class(host : $ip,port : $port),
			default => new $class
		};
	}
	public function send(string $packet) : void {
		$this->tcpClient->write($this->protocol->encode($packet));
	}
	public function receive() : string {
		return $this->protocol->decode($this->tcpClient);
	}
	public function close() : void {
		$this->tcpClient->close();
	}
	public function __destruct(){
		$this->close();
	}
}

?>