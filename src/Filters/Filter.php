<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Filters;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
abstract class Filter {
	public array $items;

	abstract public function apply(object $update) : mixed;

	static public function getFunctions(object $object,? string $unique = null) : array {
		$reflection = new \ReflectionObject($object);
		$functions = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
		$filters = [];
		foreach($functions as $function):
			$closure = $function->getClosure($object);
			$attributes = $function->getAttributes(__CLASS__); # flag : \ReflectionAttribute::IS_INSTANCEOF
			if(empty($attributes) === false):
				$hash = md5(strval(new \ReflectionFunction($closure)).strval($unique));
				$filters[$hash]['name'] = strval($reflection->getName().'::'.$function->getName());
				$filters[$hash]['callback'] = $closure;
				$filters[$hash]['attributes'] = array_map(fn(object $attribute) : array => $attribute->getArguments(),$attributes);
				$filters[$hash]['parameters'] = array();
				$parameters = $function->getParameters();
				foreach($parameters as $parameter):
					$type = $parameter->getType();
					if(is_null($type) === false):
						$conditions = match(true){
							$type instanceof \ReflectionIntersectionType => new FilterAnd($type),
							$type instanceof \ReflectionUnionType => new FilterOr($type),
							$type instanceof \ReflectionNamedType => new FilterName($type),
							default => throw new \Exception('Unknown Type Filter !')
						};
						$filters[$hash]['parameters'] []= $conditions->check(...);
					endif;
				endforeach;
			endif;
		endforeach;
		return $filters;
	}
	static public function getFunction(callable $function,? string $unique = null) : array {
		$closure = $function(...);
		$reflection = new \ReflectionFunction($closure);
		$filters = [];
		$hash = md5(strval($reflection).strval($unique));
		$filters[$hash]['name'] = strval($reflection->getName());
		$filters[$hash]['callback'] = $closure;
		$attributes = $reflection->getAttributes(__CLASS__); # flag : \ReflectionAttribute::IS_INSTANCEOF
		$filters[$hash]['attributes'] = array_map(fn(object $attribute) : array => $attribute->getArguments(),$attributes);
		$parameters = $reflection->getParameters();
		$filters[$hash]['parameters'] = array();
		foreach($parameters as $parameter):
			$type = $parameter->getType();
			if(is_null($type) === false):
				$conditions = match(true){
					$type instanceof \ReflectionIntersectionType => new FilterAnd($type),
					$type instanceof \ReflectionUnionType => new FilterOr($type),
					$type instanceof \ReflectionNamedType => new FilterName($type),
					default => throw new \Exception('Unknown Type Filter !')
				};
				$filters[$hash]['parameters'] []= $conditions->check(...);
			endif;
		endforeach;
	return $filters;
	}
}

?>