<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * PHP type reflection.
 */
final class Type
{
	/** @var array */
	private $types;

	/** @var bool */
	private $single;


	/**
	 * Creates a Type object based on reflection. Resolves self, static and parent to the actual class name.
	 * If the subject has no type, it returns null.
	 * @param  \ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty  $reflection
	 */
	public static function fromReflection($reflection): ?self
	{
		if ($reflection instanceof \ReflectionProperty && PHP_VERSION_ID < 70400) {
			return null;
		}
		$type = $reflection instanceof \ReflectionFunctionAbstract
			? $reflection->getReturnType()
			: $reflection->getType();

		if ($type === null) {
			return null;

		} elseif ($type instanceof \ReflectionNamedType) {
			$name = self::resolve($type->getName(), $reflection);
			return new self($type->allowsNull() && $type->getName() !== 'mixed' ? [$name, 'null'] : [$name]);

		} elseif ($type instanceof \ReflectionUnionType) {
			return new self(
				array_map(
					function ($t) use ($reflection) { return self::resolve($t->getName(), $reflection); },
					$type->getTypes()
				)
			);

		} else {
			throw new Nette\InvalidStateException('Unexpected type of ' . Reflection::toString($reflection));
		}
	}


	/**
	 * Resolves 'self', 'static' and 'parent' to the actual class name.
	 * @param  \ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty  $reflection
	 */
	public static function resolve(string $type, $reflection): string
	{
		$lower = strtolower($type);
		if ($reflection instanceof \ReflectionFunction) {
			return $type;
		} elseif ($lower === 'self' || $lower === 'static') {
			return $reflection->getDeclaringClass()->name;
		} elseif ($lower === 'parent' && $reflection->getDeclaringClass()->getParentClass()) {
			return $reflection->getDeclaringClass()->getParentClass()->name;
		} else {
			return $type;
		}
	}


	private function __construct(array $types)
	{
		if ($types[0] === 'null') { // null as last
			array_push($types, array_shift($types));
		}
		$this->types = $types;
		$this->single = ($types[1] ?? 'null') === 'null';
	}


	public function __toString(): string
	{
		return $this->single
			? (count($this->types) > 1 ? '?' : '') . $this->types[0]
			: implode('|', $this->types);
	}


	/**
	 * Returns the array of subtypes that make up the compound type as strings.
	 * @return string[]
	 */
	public function getNames(): array
	{
		return $this->types;
	}
}
