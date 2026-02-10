<?php

/**
 * PHPStan type tests for Utils.
 * Run: vendor/bin/phpstan analyse tests/types
 */

declare(strict_types=1);

use Nette\Iterators\CachingIterator;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays;
use Nette\Utils\Helpers;
use Nette\Utils\Html;
use Nette\Utils\Iterables;
use Nette\Utils\Type;
use Nette\Utils\Validators;
use function PHPStan\Testing\assertType;


/** @param ArrayHash<mixed> $hash */
function testArrayHash(ArrayHash $hash): void
{
	foreach ($hash as $key => $value) {
		assertType('(int|string)', $key);
		assertType('mixed', $value);
	}

	assertType('mixed', $hash['key']);
}


function testHtml(Html $html): void
{
	foreach ($html as $key => $child) {
		assertType('int', $key);
		assertType('Nette\Utils\Html|string', $child);
	}

	assertType('Nette\Utils\Html|string', $html[0]);
}


function testArraysSome(): void
{
	$result = Arrays::some([1, 2, 3], function ($value, $key) {
		assertType('1|2|3', $value);
		assertType('0|1|2', $key);
		return $value > 2;
	});
	assertType('bool', $result);
}


function testArraysEvery(): void
{
	$result = Arrays::every([1, 2, 3], function ($value, $key) {
		assertType('1|2|3', $value);
		assertType('0|1|2', $key);
		return true;
	});
	assertType('bool', $result);
}


function testArraysMap(): void
{
	$result = Arrays::map([1, 2, 3], function ($value) {
		assertType('1|2|3', $value);
		return $value * 2;
	});
	assertType('array<0|1|2, float|int>', $result);
}


/** @param array<string, int> $array */
function testArraysMapWithKeys(array $array): void
{
	$result = Arrays::mapWithKeys($array, fn($v, $k) => [$k, $v * 10]);
	assertType('array<string, float|int>', $result);
}


/** @param array<string, int> $array */
function testArraysFilter(array $array): void
{
	$result = Arrays::filter($array, fn($v) => $v > 1);
	assertType('array<string, int>', $result);
}


/** @param array<int, int> $array */
function testArraysFirst(array $array): void
{
	assertType('int|null', Arrays::first($array));
	assertType('int|null', Arrays::first($array, fn($v) => $v > 2));
}


/** @param array<string, int> $array */
function testArraysFirstKey(array $array): void
{
	assertType('string|null', Arrays::firstKey($array));
}


/** @param array<int, int> $array */
function testArraysLast(array $array): void
{
	assertType('int|null', Arrays::last($array));
}


/** @param array<string, int> $array */
function testArraysLastKey(array $array): void
{
	assertType('string|null', Arrays::lastKey($array));
}


/** @param array<int> $array */
function testArraysGet(array $array): void
{
	assertType('int|null', Arrays::get($array, 0));
	assertType('int|null', Arrays::get($array, 0, null));
}


/** @param array<int> $array */
function testArraysGetRef(array &$array): void
{
	assertType('int|null', Arrays::getRef($array, 'a'));
}


/** @param array<int|null> $array */
function testArraysPick(array &$array): void
{
	assertType('int|null', Arrays::pick($array, 'a'));
}


function testArraysToObject(): void
{
	$obj = new stdClass;
	assertType('stdClass', Arrays::toObject(['a' => 1], $obj));
}


function testHelpersClamp(): void
{
	assertType('int', Helpers::clamp(5, 1, 10));
	assertType('float', Helpers::clamp(5.0, 1, 10));
	assertType('float', Helpers::clamp(5, 1.0, 10));
	assertType('float', Helpers::clamp(5, 1, 10.0));
}


function testValidatorsIsNumeric(): void
{
	$int = 42;
	assertType('true', Validators::isNumeric($int));
	assertType('bool', Validators::isNumeric('hello'));
}


function testValidatorsIsNone(mixed $value): void
{
	if (Validators::isNone($value)) {
		assertType("0|0.0|''|false|null", $value);
	}
}


/** @param iterable<int, int> $iterable */
function testIterablesFirst(iterable $iterable): void
{
	assertType('int|null', Iterables::first($iterable));
	assertType('int|null', Iterables::first($iterable, fn($v) => $v > 2));
}


/** @param iterable<string, int> $iterable */
function testIterablesFirstKey(iterable $iterable): void
{
	assertType('string|null', Iterables::firstKey($iterable));
}


/** @param iterable<int, int> $iterable */
function testIterablesFilter(iterable $iterable): void
{
	$result = Iterables::filter($iterable, fn($v) => $v > 1);
	assertType('Generator<int, int, mixed, mixed>', $result);
}


/** @param iterable<int, int> $iterable */
function testIterablesMap(iterable $iterable): void
{
	$result = Iterables::map($iterable, fn($v) => (string) $v);
	assertType('Generator<int, string, mixed, mixed>', $result);
}


/** @param iterable<string, int> $iterable */
function testIterablesMapWithKeys(iterable $iterable): void
{
	$result = Iterables::mapWithKeys($iterable, fn($v, $k) => [$k, $v * 10]);
	assertType('Generator<string, float|int, mixed, mixed>', $result);
}


/** @param CachingIterator<string, int> $iterator */
function testCachingIterator(CachingIterator $iterator): void
{
	assertType('string', $iterator->getNextKey());
	assertType('int', $iterator->getNextValue());
}


function testTypeFromReflection(): void
{
	$ref = new ReflectionFunction('strlen');
	$type = Type::fromReflection($ref);
	assertType('Nette\Utils\Type|null', $type);
}


function testTypeGetTypes(): void
{
	$type = Type::fromString('int|string');
	assertType('list<Nette\Utils\Type>', $type->getTypes());
}


/** @param array<int, int> $array */
function testArraysFirstWithElse(array $array): void
{
	assertType('int', Arrays::first($array, else: fn() => 0));
}


/** @param array<int, int> $array */
function testArraysLastWithElse(array $array): void
{
	assertType('int', Arrays::last($array, else: fn() => 0));
}


/** @param iterable<int, int> $iterable */
function testIterablesFirstWithElse(iterable $iterable): void
{
	assertType('int', Iterables::first($iterable, else: fn() => 0));
}


/** @param iterable<string, int> $iterable */
function testIterablesFirstKeyWithElse(iterable $iterable): void
{
	assertType('string', Iterables::firstKey($iterable, else: fn() => 'default'));
}
