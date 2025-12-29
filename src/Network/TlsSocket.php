<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Amp\Socket\Socket;

use Amp\Socket\SocketException;

final class TlsSocket {
	public const HEADER_LENGTH = 0x5;
	public const LIMIT_SIZE = 2 ** 14;

	private string $buffer;
	private bool $isFirst = true;

	public function __construct(private Socket $socket){
		if($socket->isClosed()):
			throw new SocketException('Proxy closed the connection after TLS handshake');
		else:
			$this->buffer = strval(null);
		endif;
	}
	public function readexactly(int $size,? object $cancellation = null) : string {
		$result = (string) null;
		while($size > strlen($result)):
			if($this->socket->isClosed()):
				throw new SocketException('Connection closed');
			else:
				$buffer = $this->socket->read($cancellation,$size - strlen($result));
				if(is_null($buffer) === false):
					$result .= $buffer;
				else:
					$this->socket->close();
					throw new SocketException('Connection closed by remote host ( EOF ) !');
				endif;
			endif;
		endwhile;
		return $result;
	}
	private function recordTls(? object $cancellation = null) : void {
		Logging::log('Tls Handshake','TLS recording started ...');
		$header = $this->readexactly(self::HEADER_LENGTH,$cancellation);
		$size = Helper::unpack('n',substr($header,0x3,0x2));
		$read = $this->socket->read($cancellation,$size);
		if(is_null($read)):
			$this->socket->close();
			throw new SocketException('Connection closed by remote host ( EOF ) !');
		endif;
		assert(strlen($read) === $size,new SocketException('The exact size of the bytes was not read'));
		$this->buffer .= $read;
		Logging::log('Tls Handshake','A data of length '.strlen($read).' was obtained from recording TLS');
	}
	public function __call(string $name,array $arguments) : mixed {
		if($name === 'write'):
			list($data) = $arguments;
			for($offset = 0; $offset < strlen($data); $offset += self::LIMIT_SIZE):
				$chunk = substr($data,$offset,self::LIMIT_SIZE);
				$message = pack('C3',0x17,0x3,0x3).pack('n',strlen($chunk)).$chunk;
				if($this->isFirst):
					$this->socket->write(pack('C6',0x14,0x3,0x3,0x0,0x1,0x1).$message);
					$this->isFirst = false;
				else:
					$this->socket->write($message);
				endif;
				Logging::log('Tls Handshake','A message of length '.strlen($message).' and TLS header was sent');
			endfor;
			return null;
		elseif($name === 'read'):
			list($cancellation,$length) = $arguments;
			while(strlen($this->buffer) < $length):
				$this->recordTls($cancellation);
			endwhile;
			$content = substr($this->buffer,0,$length);
			$this->buffer = substr($this->buffer,$length);
			if(empty($this->buffer) === false):
				Logging::log('Tls Handshake','A buffer of length '.strlen($this->buffer).' bytes remains');
			endif;
			return $content;
		else:
			return $this->socket->$name(...$arguments);
		endif;
	}
}

?>