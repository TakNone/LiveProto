<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters;

use Tak\Liveproto\Utils\Instance;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
abstract class Middleware {
	abstract public function process(mixed ...$args) : array;

	static public function applies(array $middlewares,array $arguments) : array | false {
		foreach($middlewares as $middleware):
			try {
				$arguments = call_user_func_array($middleware,$arguments);
				if(is_array($arguments) === false):
					return false;
				endif;
			} catch(\Throwable){
				return false;
			}
		endforeach;
		return $arguments;
	}
}

?>