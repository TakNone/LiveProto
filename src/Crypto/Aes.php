<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Crypto;

abstract class Aes {
	static public function encrypt(string $plaintext,string $key,string $iv) : string {
		if(is_callable('tg_encrypt_ige')):
			return tg_encrypt_ige($plaintext,$key,$iv);
		else:
			$ivOne = substr($iv,0,0x10);
			$ivTwo = substr($iv,0x10);
			$ciphertext = strval(null);
			$padding = strlen($plaintext) % 0x10 ? 0x10 - (strlen($plaintext) % 0x10) : 0;
			$plaintext = str_pad($plaintext,strlen($plaintext) + $padding,chr(0),STR_PAD_RIGHT);
			for($i = 0,$length = strlen($plaintext); $i < $length; $i += 0x10):
				$plain = substr($plaintext,$i,0x10);
				$cipher = openssl_encrypt($plain ^ $ivOne,'AES-256-ECB',$key,OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING) ^ $ivTwo;
				$ciphertext .= $cipher;
				$ivOne = $cipher;
				$ivTwo = $plain;
			endfor;
			return $ciphertext;
		endif;
	}
	static public function decrypt(string $ciphertext,string $key,string $iv) : string {
		if(is_callable('tg_decrypt_ige')):
			return tg_decrypt_ige($ciphertext,$key,$iv);
		else:
			$ivOne = substr($iv,0,0x10);
			$ivTwo = substr($iv,0x10);
			$plaintext = strval(null);
			$padding = strlen($ciphertext) % 16 ? 0x10 - strlen($ciphertext) % 0x10 : 0;
			$ciphertext = str_pad($ciphertext,strlen($ciphertext) + $padding,chr(0),STR_PAD_RIGHT);
			for($i = 0,$length = strlen($ciphertext); $i < $length; $i += 0x10):
				$cipher = substr($ciphertext,$i,0x10);
				$plain = openssl_decrypt($cipher ^ $ivTwo,'AES-256-ECB',$key,OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING) ^ $ivOne;
				$plaintext .= $plain;
				$ivOne = $cipher;
				$ivTwo = $plain;
			endfor;
			return $plaintext;
		endif;
	}
}

?>