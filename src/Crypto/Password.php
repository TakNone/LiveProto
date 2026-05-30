<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Crypto;

use Tak\Liveproto\Errors\Security;

use Tak\Attributes\AttributesEngine;

use Tak\Liveproto\Attributes\Type;

final class Password {
	use AttributesEngine;

	protected function srp(#[Type('account.Password')] object $request,string $password) : object {
		$algo = $request->current_algo;
		$g = $algo->g;
		$p = gmp_import($algo->p);
		Security::checkGoodPrime($p,$g);
		$salt1 = $algo->salt1;
		$salt2 = $algo->salt2;
		$b = gmp_import($request->srp_B);
		$k = gmp_import($this->h($this->str($algo->p).$this->str($g)));
		$x = gmp_import($this->ph2($password,$salt1,$salt2));
		$v = gmp_powm($g,$x,$p);
		$k_v = gmp_mod(gmp_mul($k,$v),$p);
		$a = gmp_import($this->xor(random_bytes(0x100),$request->secure_random));
		$g_a = gmp_powm($g,$a,$p);
		Security::checkG(strval($g_a),$p,true);
		$u = gmp_import($this->h($this->str($g_a).$this->str($request->srp_B)));
		$g_b = gmp_mod(gmp_sub($b,$k_v),$p);
		Security::checkG(strval($g_b),$p,true);
		$s_a = gmp_powm($g_b,gmp_add($a,gmp_mul($u,$x)),$p);
		$k_a = $this->h($this->str($s_a));
		$m1 = $this->h($this->xor($this->h($this->str($algo->p)),$this->h($this->str($g))).$this->h($salt1).$this->h($salt2).$this->str($g_a).$this->str($request->srp_B).$k_a);
		$method = new \Tak\Liveproto\Tl\Types\Other\InputCheckPasswordSRP;
		return $method->request(srp_id : $request->srp_id,A : $this->str($g_a),M1 : $m1);
	}
	protected function digest(#[Type('account.Password')] object $request,string $password) : string {
		$algo = $request->new_algo;
		$g = $algo->g;
		$p = gmp_import($algo->p);
		$salt1 = $algo->salt1;
		$salt2 = $algo->salt2;
		$x = gmp_import($this->ph2($password,$salt1,$salt2));
		$v = gmp_powm($g,$x,$p);
		return $this->str($v);
	}
	private function str(int | string | \GMP $number) : string {
		return str_pad(is_string($number) ? $number : gmp_export($number),0x100,chr(0),STR_PAD_LEFT);
	}
	private function ph1(string $password,string $salt1,string $salt2) : string {
		return $this->sh($this->sh($password,$salt1),$salt2);
	}
	private function ph2(string $password,string $salt1,string $salt2) : string {
		return $this->sh(hash_pbkdf2('sha512',$this->ph1($password,$salt1,$salt2),$salt1,100000,0,true),$salt2);
	}
	private function sh(string $data,string $salt) : string {
		return $this->h($salt.$data.$salt);
	}
	private function h(string $data) : string {
		return hash('sha256',$data,true);
	}
	private function xor(string $a,string $b) : string {
		return $a ^ $b;
	}
}

?>