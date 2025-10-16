<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Crypto\Password;

use Tak\Liveproto\Enums\EmailPurpose;

use Tak\Liveproto\Enums\Authentication;

trait Account {
	public function update_password(
		#[\SensitiveParameter] ? string $password = null,
		#[\SensitiveParameter] ? string $new = null,
		? string $hint = null,
		? string $email = null
	) : bool {
		$account = $this->account->getPassword();
		$account->new_algo->salt1 .= random_bytes(32);
		$checker = new Password();
		$password = is_null($password) ? (isset($this->load->password) ? $this->load->password : null) : $password;
		if(isset($account->has_password) === false and is_null($password) === false):
			$password = null;
		endif;
		if(is_null($password) === false):
			$password = $checker->srp($account,$password);
		else:
			$password = $this->inputCheckPasswordEmpty();
		endif;
		if(is_null($new) === false):
			$new_hash = $checker->digest($account,$new);
			$new_algo = $account->new_algo;
			$hint = strval($hint);
		else:
			$new_hash = null;
			$new_algo = null;
			$hint = null;
		endif;
		$new_settings = $this->account->passwordInputSettings(new_algo : $new_algo,new_password_hash : $new_hash,hint : $hint,email : $email);
		$result = $this->account->updatePasswordSettings(password : $password,new_settings : $new_settings);
		if(is_null($new) === false and $result === true):
			$this->load->password = $new;
		endif;
		return $result;
	}
	public function send_email_code(string $email,EmailPurpose $email_purpose = EmailPurpose::LOGINSETUP) : object {
		$purpose = $this->email_purpose($email_purpose);
		$result = $this->account->sendVerifyEmailCode(purpose : $purpose,email : $email);
		if($this->load->step === Authentication::NEED_EMAIL):
			if($result instanceof \Tak\Liveproto\Tl\Types\Account\SentEmailCode):
				$this->load->step = Authentication::NEED_EMAIL_VERIFY;
			endif;
		endif;
		return $result;
	}
	public function verify_email(string | int $code,EmailPurpose $email_purpose = EmailPurpose::LOGINSETUP) : object {
		$purpose = $this->email_purpose($email_purpose);
		$verification = $this->emailVerificationCode(code : strval($code));
		$result = $this->account->verifyEmail(purpose : $purpose,verification : $verification);
		if($result instanceof \Tak\Liveproto\Tl\Types\Account\EmailVerifiedLogin):
			$result->sent_code = $this->apply_sent_code($result->sent_code);
		endif;
		return $result;
	}
	private function email_purpose(EmailPurpose $purpose) : object {
		$args = match($purpose){
			EmailPurpose::LOGINSETUP => array('phone_number'=>$this->load->phonenumber,'phone_code_hash'=>$this->load->phonecodehash),
			EmailPurpose::LOGINCHANGE => array(),
			EmailPurpose::PASSPORT => array(),
			default => \Exception('Unknown email purpose !')
		};
		return call_user_func(array($this,$purpose->value),...$args);
	}
}

?>