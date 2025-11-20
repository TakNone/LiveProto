<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network;

use Tak\Liveproto\Utils\Tools;

use Tak\Liveproto\Utils\Helper;

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Utils\TlsHello;

use Amp\Socket\Socket;

use Amp\Socket\SocketException;

use League\Uri\Http;

# https://github.com/DrKLO/Telegram/blob/ddc90f16be1ab952114005347e0102365ba6460b/TMessagesProj/jni/tgnet/ConnectionSocket.cpp #
final class TlsHandshake {
	public string $secret;
	public string $secretDomain;

	public function __construct(public string $target,public array $proxy){
		list($this->secret,$this->secretDomain) = $this->separate(strval($proxy['secret']));
	}
	public function exchange(Socket $socket) : object {
		$this->doHandshake($socket);
		return new class($socket){
			public const HEADER_LENGTH = 0x5;
			public const LIMIT_SIZE = 2 ** 14;

			private string $buffer;

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
					$data = reset($arguments);
					for($offset = 0; $offset < strlen($data); $offset += self::LIMIT_SIZE):
						$chunk = substr($data,$offset,self::LIMIT_SIZE);
						$message = pack('C3',0x17,0x3,0x3).pack('n',strlen($chunk)).$chunk;
						$this->socket->write($message);
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
		};
	}
	public function doHandshake(Socket $socket) : void {
		$hello = new TlsHello($this->secretDomain);
		$buffer = $hello->writeToBuffer();
		$padded = $hello->writePadding($buffer);
		if(empty($padded) === false):
			$hmac = hash_hmac('sha256',$padded,$this->secret,true);
			$timeBytes = pack('V',time());
			$hmacBytes = substr($hmac,0,28).($timeBytes ^ substr($hmac,28,4));
			$tempBuffer = substr($padded,0,11).$hmacBytes.substr($padded,11 + 32);
			$socket->write($tempBuffer);
		endif;
		$chunk = null;
		readBuffer:
			do {
				if($socket->isClosed()):
					throw new SocketException('The connection may have been closed due to sending incorrect information to the proxy');
				else:
					$chunk .= $socket->read() ?? throw new \RuntimeException('Connection closed by remote host ( EOF ) !');
				endif;
			} while(empty($chunk));
		$length = strlen($chunk);
		if($length > 64 * 1024):
			Logging::log('Tls Handshake','TLS client hello too much data',E_ERROR);
			$socket->close();
		elseif($length >= 16):
			if(substr($chunk,0,3) === pack('C3',0x16,0x3,0x3)):
				$len1 = intval(ord($chunk[3]) << 8) | ord($chunk[4]);
				if($len1 > 64 * 1024 - 5):
					Logging::log('Tls Handshake','TLS len1 invalid',E_ERROR);
					$socket->close();
				elseif($length >= intval($hello2Start = $len1 + 5)):
					if(substr($chunk,$hello2Start,9) === pack('C9',0x14,0x3,0x3,0x0,0x1,0x1,0x17,0x3,0x3)):
						$len2 = intval(ord($chunk[$hello2Start + 9]) << 8) | ord($chunk[$hello2Start + 10]);
						if($len2 <= 64 * 1024 - $len1 - 5 - 11):
							if($length >= $len2 + $len1 + 5 + 11):
								$prefix = substr($tempBuffer,11,32);
								$payload = substr_replace($chunk,str_repeat(chr(0),32),11,32);
								$hmac = hash_hmac('sha256',$prefix.$payload,$this->secret,true);
								if(hash_equals(substr($chunk,11,32),$hmac)):
									Logging::log('Tls Handshake','TLS hello complete');
								else:
									Logging::log('Tls Handshake','TLS hash mismatch',E_ERROR);
									$socket->close();
								endif;
							else:
								Logging::log('Tls Handshake','TLS client hello wait for more data',E_NOTICE);
								goto readBuffer;
							endif;
						else:
							Logging::log('Tls Handshake','TLS len2 invalid',E_ERROR);
							$socket->close();
						endif;
					else:
						Logging::log('Tls Handshake','TLS hello2 mismatch',E_ERROR);
						$socket->close();
					endif;
				else:
					Logging::log('Tls Handshake','TLS client hello wait for more data',E_NOTICE);
					goto readBuffer;
				endif;
			else:
				Logging::log('Tls Handshake','TLS hello1 mismatch',E_ERROR);
				$socket->close();
			endif;
		endif;
	}
	public function separate(string $secret) : array {
		$bytes = ctype_xdigit($secret) ? hex2bin($secret) : Tools::base64_url_decode($secret);
		$raw =  substr($bytes,intval(strlen($bytes) > 17 and strcasecmp($secret,'ee') === 1));
		return array(substr($raw,0,16),substr($raw,16));
	}
}

?>