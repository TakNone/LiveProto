<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Attributes;

use Tak\Liveproto\Parser\Tl;

use Tak\Liveproto\Utils\Instance;

use Tak\Attributes\ValidatorInterface;

use Attribute;

use InvalidArgumentException;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class Type implements ValidatorInterface {
	protected array $types;

	public function __construct(string ...$types){
		$this->types = array_filter(array_map(trim(...),$types));
	}
	public function validate(string $name,mixed $value) : mixed {
		if($value instanceof Instance === false){
			throw new InvalidArgumentException('$'.$name.' must be Instance');
		}
		$type = Tl::parseReturn($value);
		if(in_array($type,$this->types,true) === false){
			throw new InvalidArgumentException('$'.$name.' must be'.strval(count($this->types) > 1 ? ' one of ' : chr(32)).implode(chr(32).chr(44).chr(32),$this->types).' type , given '.$type);
		}
		return $value;
	}
}

?>