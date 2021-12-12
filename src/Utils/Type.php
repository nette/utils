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
	private array $types;
	private bool $single;
	private string $kind; // | &


	/**
	 * Creates a Type object based on reflection. Resolves self, static and parent to the actual class name.
	 * If the subject has no type, it returns null.
	 */
	public static function fromReflection(
		\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty $reflection,
	): ?self {
		if ($reflection instanceof \ReflectionMethod) {
			$type = $reflection->getReturnType() ?? (PHP_VERSION_ID >= 80100 ? $reflection->getTentativeReturnType() : null);
		} else {
			$type = $reflection instanceof \ReflectionFunctionAbstract
				? $reflection->getReturnType()
				: $reflection->getType();
		}

		if ($type === null) {
			return null;

		} elseif ($type instanceof \ReflectionNamedType) {
			$name = self::resolve($type->getName(), $reflection);
			return new self($type->allowsNull() && $type->getName() !== 'mixed' ? [$name, 'null'] : [$name]);

		} elseif ($type instanceof \ReflectionUnionType || $type instanceof \ReflectionIntersectionType) {
			return new self(
				array_map(
					fn($t) => self::resolve($t->getName(), $reflection),
					$type->getTypes(),
				),
				$type instanceof \ReflectionUnionType ? '|' : '&',
			);

		} else {
			throw new Nette\InvalidStateException('Unexpected type of ' . Reflection::toString($reflection));
		}
	}


	/**
	 * Creates the Type object according to the text notation.
	 */
	public static function fromString(string $type): self
	{
		if (!preg_match('#(?:
			\?([\w\\\\]+)|
			[\w\\\\]+ (?: (&[\w\\\\]+)* | (\|[\w\\\\]+)* )
		)()$#xAD', $type, $m)) {
			throw new Nette\InvalidArgumentException("Invalid type '$type'.");
		}

		[, $nType, $iType] = $m;
		if ($nType) {
			return new self([$nType, 'null']);
		} elseif ($iType) {
			return new self(explode('&', $type), '&');
		} else {
			return new self(explode('|', $type));
		}
	}


	/**
	 * Resolves 'self', 'static' and 'parent' to the actual class name.
	 */
	public static function resolve(
		string $type,
		\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty $reflection,
	): string {
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


	private function __construct(array $types, string $kind = '|')
	{
		if ($types[0] === 'null') { // null as last
			array_push($types, array_shift($types));
		}

		$this->types = $types;
		$this->single = ($types[1] ?? 'null') === 'null';
		$this->kind = count($types) > 1 ? $kind : '';
	}


	public function __toString(): string
	{
		return $this->single
			? (count($this->types) > 1 ? '?' : '') . $this->types[0]
			: implode($this->kind, $this->types);
	}


	/**
	 * Returns the array of subtypes that make up the compound type as strings.
	 * @return string[]
	 */
	public function getNames(): array
	{
		return $this->types;
	}


	/**
	 * Returns the array of subtypes that make up the compound type as Type objects:
	 * @return self[]
	 */
	public function getTypes(): array
	{
		return array_map(fn($name) => self::fromString($name), $this->types);
	}


	/**
	 * Returns the type name for single types, otherwise null.
	 */
	public function getSingleName(): ?string
	{
		return $this->single
			? $this->types[0]
			: null;
	}


	/**
	 * Returns true whether it is a union type.
	 */
	public function isUnion(): bool
	{
		return $this->kind === '|';
	}


	/**
	 * Returns true whether it is an intersection type.
	 */
	public function isIntersection(): bool
	{
		return $this->kind === '&';
	}


	/**
	 * Returns true whether it is a single type. Simple nullable types are also considered to be single types.
	 */
	public function isSingle(): bool
	{
		return $this->single;
	}


	/**
	 * Returns true whether the type is both a single and a PHP built-in type.
	 */
	public function isBuiltin(): bool
	{
		return $this->single && Reflection::isBuiltinType($this->types[0]);
	}


	/**
	 * Returns true whether the type is both a single and a class name.
	 */
	public function isClass(): bool
	{
		return $this->single && !Reflection::isBuiltinType($this->types[0]);
	}


	/**
	 * Determines if type is special class name self/parent/static.
	 */
	public function isClassKeyword(): bool
	{
		return $this->single && Reflection::isClassKeyword($this->types[0]);
	}


	/**
	 * Verifies type compatibility. For example, it checks if a value of a certain type could be passed as a parameter.
	 */
	public function allows(string $type): bool
	{
		if ($this->types === ['mixed']) {
			return true;
		}

		$type = self::fromString($type);

		if ($this->isIntersection()) {
			return $type->isIntersection()
				&& Arrays::every($this->types, function ($currentType) use ($type) {
					$builtin = Reflection::isBuiltinType($currentType);
					return Arrays::some($type->types, fn($testedType) => $builtin
						? strcasecmp($currentType, $testedType) === 0
						: is_a($testedType, $currentType, true));
				});
		}

		$method = $type->isIntersection() ? 'some' : 'every';
		return Arrays::$method($type->types, function ($testedType) {
			$builtin = Reflection::isBuiltinType($testedType);
			return Arrays::some($this->types, fn($currentType) => $builtin
				? strcasecmp($currentType, $testedType) === 0
				: is_a($testedType, $currentType, true));
		});
	}
}
