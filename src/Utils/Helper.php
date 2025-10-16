<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Utils;

use Tak\Liveproto\Enums\Endianness;

abstract class Helper {
	static public function unpack(string $format,string $string,int $offset = 0,Endianness $byteorder = Endianness::LITTLE) : mixed {
		$un = unpack($format,$byteorder->isBig() ? strrev($string) : $string,$offset);
		$result = is_array($un) ? $un[true] : $un;
		return $result;
	}
	static public function pack(string $format,mixed $value,Endianness $byteorder = Endianness::LITTLE) : string {
		return pack($format,$byteorder->isLittle() ? $value : strrev(strval($value)));
	}
	static public function generateRandomLong() : int {
		$long = random_int(PHP_INT_MIN,PHP_INT_MAX);
		return $long === 0 ? call_user_func(__METHOD__) : $long;
	}
	static public function generateRandomLargeInt(int $bits = 0x40) : string {
		$bytes = intdiv($bits,8);
		return strval(gmp_import(random_bytes($bytes),$bytes));
	}
	static public function generateRandomString(int $length = 0x10) : string {
		return substr(str_shuffle(implode([...range('A','Z'),...range('a','z'),...range(0,9)])),-abs($length));
	}
	/* V2 : https://core.telegram.org/mtproto/description#defining-aes-key-and-initialization-vector */
	static public function keyCalculate(string $authKey,string $msgKey,bool $client) : array {
		$x = $client ? 0x0 : 0x8;
		$a = hash('sha256',$msgKey.substr($authKey,$x,0x24),true);
		$b = hash('sha256',substr($authKey,0x28 + $x,0x24).$msgKey,true);
		$key = substr($a,0x0,0x8).substr($b,0x8,0x10).substr($a,0x18,0x8);
		$iv = substr($b,0x0,0x8).substr($a,0x8,0x10).substr($b,0x18,0x8);
		return [$key,$iv];
	}
	/* V1 : https://core.telegram.org/mtproto/description_v1#defining-aes-key-and-initialization-vector */
	static public function aesCalculate(string $authKey,string $msgKey,bool $client) : array {
		$x = $client ? 0x0 : 0x8;
		$a = sha1($msgKey.substr($authKey,$x,0x20),true);
		$b = sha1(substr($authKey,$x + 0x20,0x10).$msgKey.substr($authKey,$x + 0x30,0x10),true);
		$c = sha1(substr($authKey,$x + 0x40,0x20).$msgKey,true);
		$d = sha1($msgKey.substr($authKey,$x + 0x60,0x20),true);
		$key = substr($a,0x0,0x8).substr($b,0x8,0xc).substr($c,0x4,0xc);
		$iv = substr($a,0x8,0xc).substr($b,0x0,0x8).substr($c,0x10,0x4).substr($d,0x0,0x8);
		return [$key,$iv];
	}
	static public function generateKeyNonces(string $serverNonce,string $newNonce) : array {
		$hash1 = sha1($newNonce.$serverNonce,true);
		$hash2 = sha1($serverNonce.$newNonce,true);
		$hash3 = sha1($newNonce.$newNonce,true);
		$key = $hash1.substr($hash2,0x0,0xc);
		$iv = substr($hash2,0xc,0x8).$hash3.substr($newNonce,0x0,0x4);
		return [$key,$iv];
	}
	# https://core.telegram.org/api/offsets#hash-generation #
	static public function hashGeneration(int $hash = 0,array $ids = array()) : int {
		foreach($ids as $id):
			if(is_string($id)):
				$id = self::unpack('P',substr(md5($id,true),0,8),Endianness::BIG);
			else:
				$id = intval($id);
			endif;
			$hash = $hash ^ ($hash >> 21) & ~ ( 1 << (8 * PHP_INT_SIZE - 1) >> 20);
			$hash = $hash ^ ($hash << 35);
			$hash = $hash ^ ($hash >> 4) & ~ ( 1 << (8 * PHP_INT_SIZE - 1) >> 3);
			$hash = $hash + $id;
		endforeach;
		return $hash;
	}
}

?>