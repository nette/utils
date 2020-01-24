<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * PHP reflection helpers.
 */
final class Reflection
{
	use Nette\StaticClass;

	private const BUILTIN_TYPES = [
		'string' => 1, 'int' => 1, 'float' => 1, 'bool' => 1, 'array' => 1, 'object' => 1,
		'callable' => 1, 'iterable' => 1, 'void' => 1, 'null' => 1,
	];


	public static function isBuiltinType(string $type): bool
	{
		return isset(self::BUILTIN_TYPES[strtolower($type)]);
	}


	public static function getReturnType(\ReflectionFunctionAbstract $func): ?string
	{
		$type = $func->getReturnType();
		return $type instanceof \ReflectionNamedType && $func instanceof \ReflectionMethod
			? self::normalizeType($type->getName(), $func)
			: null;
	}


	public static function getParameterType(\ReflectionParameter $param): ?string
	{
		$type = $param->getType();
		return $type instanceof \ReflectionNamedType
			? self::normalizeType($type->getName(), $param)
			: null;
	}


	public static function getPropertyType(\ReflectionProperty $prop): ?string
	{
		$type = PHP_VERSION_ID >= 70400 ? $prop->getType() : null;
		return $type instanceof \ReflectionNamedType
			? self::normalizeType($type->getName(), $prop)
			: null;
	}


	/**
	 * @param  \ReflectionMethod|\ReflectionParameter|\ReflectionProperty  $reflection
	 */
	private static function normalizeType(string $type, $reflection): string
	{
		$lower = strtolower($type);
		if ($lower === 'self') {
			return $reflection->getDeclaringClass()->getName();
		} elseif ($lower === 'parent' && $reflection->getDeclaringClass()->getParentClass()) {
			return $reflection->getDeclaringClass()->getParentClass()->getName();
		} else {
			return $type;
		}
	}


	/**
	 * @return mixed
	 * @throws \ReflectionException when default value is not available or resolvable
	 */
	public static function getParameterDefaultValue(\ReflectionParameter $param)
	{
		if ($param->isDefaultValueConstant()) {
			$const = $orig = $param->getDefaultValueConstantName();
			$pair = explode('::', $const);
			if (isset($pair[1])) {
				$pair[0] = self::normalizeType($pair[0], $param);
				try {
					$rcc = new \ReflectionClassConstant($pair[0], $pair[1]);
				} catch (\ReflectionException $e) {
					$name = self::toString($param);
					throw new \ReflectionException("Unable to resolve constant $orig used as default value of $name.", 0, $e);
				}
				return $rcc->getValue();

			} elseif (!defined($const)) {
				$const = substr((string) strrchr($const, '\\'), 1);
				if (!defined($const)) {
					$name = self::toString($param);
					throw new \ReflectionException("Unable to resolve constant $orig used as default value of $name.");
				}
			}
			return constant($const);
		}
		return $param->getDefaultValue();
	}


	/**
	 * Returns declaring class or trait.
	 */
	public static function getPropertyDeclaringClass(\ReflectionProperty $prop): \ReflectionClass
	{
		foreach ($prop->getDeclaringClass()->getTraits() as $trait) {
			if ($trait->hasProperty($prop->getName())
				&& $trait->getProperty($prop->getName())->getDocComment() === $prop->getDocComment()
			) {
				return self::getPropertyDeclaringClass($trait->getProperty($prop->getName()));
			}
		}
		return $prop->getDeclaringClass();
	}


	/**
	 * Are documentation comments available?
	 */
	public static function areCommentsAvailable(): bool
	{
		static $res;
		return $res === null
			? $res = (bool) (new \ReflectionMethod(__METHOD__))->getDocComment()
			: $res;
	}


	public static function toString(\Reflector $ref): string
	{
		if ($ref instanceof \ReflectionClass) {
			return $ref->getName();
		} elseif ($ref instanceof \ReflectionMethod) {
			return $ref->getDeclaringClass()->getName() . '::' . $ref->getName();
		} elseif ($ref instanceof \ReflectionFunction) {
			return $ref->getName();
		} elseif ($ref instanceof \ReflectionProperty) {
			return self::getPropertyDeclaringClass($ref)->getName() . '::$' . $ref->getName();
		} elseif ($ref instanceof \ReflectionParameter) {
			return '$' . $ref->getName() . ' in ' . self::toString($ref->getDeclaringFunction()) . '()';
		} else {
			throw new Nette\InvalidArgumentException;
		}
	}


	/**
	 * Expands class name into full name.
	 * @throws Nette\InvalidArgumentException
	 */
	public static function expandClassName(string $name, \ReflectionClass $rc): string
	{
		$lower = strtolower($name);
		if (empty($name)) {
			throw new Nette\InvalidArgumentException('Class name must not be empty.');

		} elseif (isset(self::BUILTIN_TYPES[$lower])) {
			return $lower;

		} elseif ($lower === 'self') {
			return $rc->getName();

		} elseif ($name[0] === '\\') { // fully qualified name
			return ltrim($name, '\\');
		}

		$uses = self::getUseStatements($rc);
		$parts = explode('\\', $name, 2);
		if (isset($uses[$parts[0]])) {
			$parts[0] = $uses[$parts[0]];
			return implode('\\', $parts);

		} elseif ($rc->inNamespace()) {
			return $rc->getNamespaceName() . '\\' . $name;

		} else {
			return $name;
		}
	}


	/** @return array of [alias => class] */
	public static function getUseStatements(\ReflectionClass $class): array
	{
		if ($class->isAnonymous()) {
			throw new Nette\NotImplementedException('Anonymous classes are not supported.');
		}
		static $cache = [];
		if (!isset($cache[$name = $class->getName()])) {
			if ($class->isInternal()) {
				$cache[$name] = [];
			} else {
				$code = file_get_contents($class->getFileName());
				$cache = self::parseUseStatements($code, $name) + $cache;
			}
		}
		return $cache[$name];
	}


	/**
	 * Parses PHP code to [class => [alias => class, ...]]
	 */
	private static function parseUseStatements(string $code, string $forClass = null): array
	{
		try {
			$tokens = token_get_all($code, TOKEN_PARSE);
		} catch (\ParseError $e) {
			trigger_error($e->getMessage(), E_USER_NOTICE);
			$tokens = [];
		}
		$namespace = $class = $classLevel = $level = null;
		$res = $uses = [];

		while ($token = current($tokens)) {
			next($tokens);
			switch (is_array($token) ? $token[0] : $token) {
				case T_NAMESPACE:
					$namespace = ltrim(self::fetch($tokens, [T_STRING, T_NS_SEPARATOR]) . '\\', '\\');
					$uses = [];
					break;

				case T_CLASS:
				case T_INTERFACE:
				case T_TRAIT:
					if ($name = self::fetch($tokens, T_STRING)) {
						$class = $namespace . $name;
						$classLevel = $level + 1;
						$res[$class] = $uses;
						if ($class === $forClass) {
							return $res;
						}
					}
					break;

				case T_USE:
					while (!$class && ($name = self::fetch($tokens, [T_STRING, T_NS_SEPARATOR]))) {
						$name = ltrim($name, '\\');
						if (self::fetch($tokens, '{')) {
							while ($suffix = self::fetch($tokens, [T_STRING, T_NS_SEPARATOR])) {
								if (self::fetch($tokens, T_AS)) {
									$uses[self::fetch($tokens, T_STRING)] = $name . $suffix;
								} else {
									$tmp = explode('\\', $suffix);
									$uses[end($tmp)] = $name . $suffix;
								}
								if (!self::fetch($tokens, ',')) {
									break;
								}
							}

						} elseif (self::fetch($tokens, T_AS)) {
							$uses[self::fetch($tokens, T_STRING)] = $name;

						} else {
							$tmp = explode('\\', $name);
							$uses[end($tmp)] = $name;
						}
						if (!self::fetch($tokens, ',')) {
							break;
						}
					}
					break;

				case T_CURLY_OPEN:
				case T_DOLLAR_OPEN_CURLY_BRACES:
				case '{':
					$level++;
					break;

				case '}':
					if ($level === $classLevel) {
						$class = $classLevel = null;
					}
					$level--;
			}
		}

		return $res;
	}


	private static function fetch(array &$tokens, $take): ?string
	{
		$res = null;
		while ($token = current($tokens)) {
			[$token, $s] = is_array($token) ? $token : [$token, $token];
			if (in_array($token, (array) $take, true)) {
				$res .= $s;
			} elseif (!in_array($token, [T_DOC_COMMENT, T_WHITESPACE, T_COMMENT], true)) {
				break;
			}
			next($tokens);
		}
		return $res;
	}
}
