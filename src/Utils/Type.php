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
	private $simple;

	/** @var string  |, & */
	private $kind;


	/**
	 * Creates a Type object based on reflection. Resolves self, static and parent to the actual class name.
	 * If the subject has no type, it returns null.
	 * @param  \ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty  $reflection
	 */
	public static function fromReflection($reflection): ?self
	{
		if ($reflection instanceof \ReflectionProperty && PHP_VERSION_ID < 70400) {
			return null;
		} elseif ($reflection instanceof \ReflectionMethod) {
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
					function ($t) use ($reflection) { return self::resolve($t->getName(), $reflection); },
					$type->getTypes()
				),
				$type instanceof \ReflectionUnionType ? '|' : '&'
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
	 * @param  \ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty  $of
	 */
	public static function resolve(string $type, $of): string
	{
		$lower = strtolower($type);
		if ($of instanceof \ReflectionFunction) {
			return $type;
		} elseif ($lower === 'self' || $lower === 'static') {
			return $of->getDeclaringClass()->name;
		} elseif ($lower === 'parent' && $of->getDeclaringClass()->getParentClass()) {
			return $of->getDeclaringClass()->getParentClass()->name;
		} else {
			return $type;
		}
	}


	private function __construct(array $types, string $kind = '|')
	{
		$o = array_search('null', $types, true);
		if ($o !== false) { // null as last
			array_splice($types, $o, 1);
			$types[] = 'null';
		}

		$this->types = $types;
		$this->simple = ($types[1] ?? 'null') === 'null';
		$this->kind = count($types) > 1 ? $kind : '';
	}


	public function __toString(): string
	{
		return $this->simple
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
		return array_map(function ($name) { return self::fromString($name); }, $this->types);
	}


	/**
	 * Returns the type name for simple types, otherwise null.
	 */
	public function getSingleName(): ?string
	{
		return $this->simple
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
	 * Returns true whether it is a simple type. Single nullable types are also considered to be simple types.
	 */
	public function isSimple(): bool
	{
		return $this->simple;
	}


	/** @deprecated use isSimple() */
	public function isSingle(): bool
	{
		return $this->simple;
	}


	/**
	 * Returns true whether the type is both a simple and a PHP built-in type.
	 */
	public function isBuiltin(): bool
	{
		return $this->simple && Validators::isBuiltinType($this->types[0]);
	}


	/**
	 * Returns true whether the type is both a simple and a class name.
	 */
	public function isClass(): bool
	{
		return $this->simple && !Validators::isBuiltinType($this->types[0]);
	}


	/**
	 * Determines if type is special class name self/parent/static.
	 */
	public function isClassKeyword(): bool
	{
		return $this->simple && Validators::isClassKeyword($this->types[0]);
	}


	/**
	 * Verifies type compatibility. For example, it checks if a value of a certain type could be passed as a parameter.
	 */
	public function allows(string $subtype): bool
	{
		if ($this->types === ['mixed']) {
			return true;
		}

		$subtype = self::fromString($subtype);

		if ($this->isIntersection()) {
			if (!$subtype->isIntersection()) {
				return false;
			}

			return Arrays::every($this->types, function ($currentType) use ($subtype) {
				$builtin = Reflection::isBuiltinType($currentType);
				return Arrays::some($subtype->types, function ($testedType) use ($currentType, $builtin) {
					return $builtin
						? strcasecmp($currentType, $testedType) === 0
						: is_a($testedType, $currentType, true);
				});
			});
		}

		$method = $subtype->isIntersection() ? 'some' : 'every';
		return Arrays::$method($subtype->types, function ($testedType) {
			$builtin = Validators::isBuiltinType($testedType);
			return Arrays::some($this->types, function ($currentType) use ($testedType, $builtin) {
				return $builtin
					? strcasecmp($currentType, $testedType) === 0
					: is_a($testedType, $currentType, true);
			});
		});
	}
}
