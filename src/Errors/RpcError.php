<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Errors;

use Exception;

use function Tak\Asyncio\File\read;

final class RpcError extends Exception {
	protected string $type;
	protected string $description;
	protected int $value;

	protected const CODES = [
		303=>'SEE_OTHER',
		400=>'BAD_REQUEST',
		401=>'UNAUTHORIZED',
		403=>'FORBIDDEN',
		404=>'NOT_FOUND',
		406=>'NOT_ACCEPTABLE',
		420=>'FLOOD',
		500=>'INTERNAL'
	];

	public function __construct(string $message,int $code = 0){
		$this->type = isset(self::CODES[$code]) ? self::CODES[$code] : 'UNKNOWN';
		$this->description = 'UNKNOWN';
		$this->value = 0;
		$descriptions = $this->fetchErrorDescriptions();
		foreach($descriptions as $name => $description):
			$serial = sscanf($message,$name);
			$hasValue = boolval(empty($serial) === false and in_array(null,$serial) === false);
			if($message === $name or $hasValue):
				if($hasValue):
					$this->value = intval($serial[false]);
				endif;
				$this->description = sprintf($description,...$serial);
				break;
			endif;
		endforeach;
		parent::__construct($message,$code);
	}
	public function getType() : string {
		return $this->type;
	}
	public function getDescription() : string {
		return $this->description;
	}
	public function getValue() : int {
		return $this->value;
	}
	private function fetchErrorDescriptions() : array {
		static $descriptions;
		if(isset($descriptions) === false):
			$descriptions = json_decode(read(__DIR__.DIRECTORY_SEPARATOR.'errors.json'),associative : true,flags : JSON_THROW_ON_ERROR);
		endif;
		return $descriptions;
	}
	public function __toString(){
		return $this->getMessage().chr(32).$this->getCode().chr(32).chr(58).chr(32).$this->getDescription();
	}
}

?>