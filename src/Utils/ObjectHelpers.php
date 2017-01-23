<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;
use Nette\MemberAccessException;


/**
 * Nette\SmartObject helpers.
 */
final class ObjectHelpers
{
	use Nette\StaticClass;

	/**
	 * @throws MemberAccessException
	 */
	public static function strictGet(string $class, string $name)
	{
		$rc = new \ReflectionClass($class);
		$hint = self::getSuggestion(array_merge(
			array_filter($rc->getProperties(\ReflectionProperty::IS_PUBLIC), function ($p) { return !$p->isStatic(); }),
			self::parseFullDoc($rc, '~^[ \t*]*@property(?:-read)?[ \t]+(?:\S+[ \t]+)??\$(\w+)~m')
		), $name);
		throw new MemberAccessException("Cannot read an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
	}


	/**
	 * @throws MemberAccessException
	 */
	public static function strictSet(string $class, string $name)
	{
		$rc = new \ReflectionClass($class);
		$hint = self::getSuggestion(array_merge(
			array_filter($rc->getProperties(\ReflectionProperty::IS_PUBLIC), function ($p) { return !$p->isStatic(); }),
			self::parseFullDoc($rc, '~^[ \t*]*@property(?:-write)?[ \t]+(?:\S+[ \t]+)??\$(\w+)~m')
		), $name);
		throw new MemberAccessException("Cannot write to an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
	}


	/**
	 * @throws MemberAccessException
	 */
	public static function strictCall(string $class, string $method, array $additionalMethods = [])
	{
		$hint = self::getSuggestion(array_merge(
			get_class_methods($class),
			self::parseFullDoc(new \ReflectionClass($class), '~^[ \t*]*@method[ \t]+(?:\S+[ \t]+)??(\w+)\(~m'),
			$additionalMethods
		), $method);

		if (method_exists($class, $method)) { // called parent::$method()
			$class = 'parent';
		}
		throw new MemberAccessException("Call to undefined method $class::$method()" . ($hint ? ", did you mean $hint()?" : '.'));
	}


	/**
	 * @throws MemberAccessException
	 */
	public static function strictStaticCall(string $class, string $method)
	{
		$hint = self::getSuggestion(
			array_filter((new \ReflectionClass($class))->getMethods(\ReflectionMethod::IS_PUBLIC), function ($m) { return $m->isStatic(); }),
			$method
		);
		throw new MemberAccessException("Call to undefined static method $class::$method()" . ($hint ? ", did you mean $hint()?" : '.'));
	}


	/**
	 * Returns array of magic properties defined by annotation @property.
	 * @return array of [name => bit mask]
	 * @internal
	 */
	public static function getMagicProperties(string $class): array
	{
		static $cache;
		$props = &$cache[$class];
		if ($props !== NULL) {
			return $props;
		}

		$rc = new \ReflectionClass($class);
		preg_match_all(
			'~^  [ \t*]*  @property(|-read|-write)  [ \t]+  [^\s$]+  [ \t]+  \$  (\w+)  ()~mx',
			(string) $rc->getDocComment(), $matches, PREG_SET_ORDER
		);

		$props = [];
		foreach ($matches as list(, $type, $name)) {
			$uname = ucfirst($name);
			$write = $type !== '-read'
				&& $rc->hasMethod($nm = 'set' . $uname)
				&& ($rm = $rc->getMethod($nm)) && $rm->getName() === $nm && !$rm->isPrivate() && !$rm->isStatic();
			$read = $type !== '-write'
				&& ($rc->hasMethod($nm = 'get' . $uname) || $rc->hasMethod($nm = 'is' . $uname))
				&& ($rm = $rc->getMethod($nm)) && $rm->getName() === $nm && !$rm->isPrivate() && !$rm->isStatic();

			if ($read || $write) {
				$props[$name] = $read << 0 | ($nm[0] === 'g') << 1 | $rm->returnsReference() << 2 | $write << 3;
			}
		}

		foreach ($rc->getTraits() as $trait) {
			$props += self::getMagicProperties($trait->getName());
		}

		if ($parent = get_parent_class($class)) {
			$props += self::getMagicProperties($parent);
		}
		return $props;
	}


	/**
	 * Finds the best suggestion (for 8-bit encoding).
	 * @return string|NULL
	 * @internal
	 */
	public static function getSuggestion(array $possibilities, string $value)
	{
		$norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
		$best = NULL;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($possibilities, SORT_REGULAR) as $item) {
			$item = $item instanceof \Reflector ? $item->getName() : $item;
			if ($item !== $value && (
				($len = levenshtein($item, $value, 10, 11, 10)) < $min
				|| ($len = levenshtein(preg_replace($re, '', $item), $norm, 10, 11, 10) + 20) < $min
			)) {
				$min = $len;
				$best = $item;
			}
		}
		return $best;
	}


	private static function parseFullDoc(\ReflectionClass $rc, string $pattern): array
	{
		do {
			$doc[] = $rc->getDocComment();
			$traits = $rc->getTraits();
			while ($trait = array_pop($traits)) {
				$doc[] = $trait->getDocComment();
				$traits += $trait->getTraits();
			}
		} while ($rc = $rc->getParentClass());
		return preg_match_all($pattern, implode($doc), $m) ? $m[1] : [];
	}


	/**
	 * Checks if the public non-static property exists.
	 * @return bool|'event'
	 * @internal
	 */
	public static function hasProperty(string $class, string $name)
	{
		static $cache;
		$prop = &$cache[$class][$name];
		if ($prop === NULL) {
			$prop = FALSE;
			try {
				$rp = new \ReflectionProperty($class, $name);
				if ($rp->isPublic() && !$rp->isStatic()) {
					$prop = $name >= 'onA' && $name < 'on_' ? 'event' : TRUE;
				}
			} catch (\ReflectionException $e) {
			}
		}
		return $prop;
	}

}
