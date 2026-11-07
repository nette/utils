<?php declare(strict_types=1);

/**
 * PHPStan type tests for Utils.
 * Run: vendor/bin/phpstan analyse tests/types
 */

use Nette\Iterators\CachingIterator;
use Nette\Utils\{ArrayHash, ArrayList, Arrays, Helpers, Html, Iterables, Random, Strings, Type, Validators};
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


/** @param non-empty-string $nonEmpty */
function testStringsLength(string $string, string $nonEmpty): void
{
	assertType('int<1, max>', Strings::length('abc'));
	assertType('int<1, max>', Strings::length($nonEmpty));
	assertType('0', Strings::length(''));
	assertType('int<0, max>', Strings::length($string));
}


/** @param non-empty-string $nonEmpty */
function testStringsCaseConversion(string $string, string $nonEmpty): void
{
	assertType('non-empty-string', Strings::lower($nonEmpty));
	assertType('non-empty-string', Strings::firstLower($nonEmpty));
	assertType('non-empty-string', Strings::upper('abc'));
	assertType('non-empty-string', Strings::firstUpper($nonEmpty));
	assertType('non-empty-string', Strings::capitalize($nonEmpty));
	assertType('non-empty-string', Strings::reverse($nonEmpty));
	assertType('string', Strings::lower($string));
	assertType('string', Strings::reverse($string));
}


function testStringsIndexOf(string $string): void
{
	assertType('int<0, max>|null', Strings::indexOf($string, 'x'));
}


function testRandomGenerate(int $length): void
{
	assertType('non-empty-string', Random::generate());
	assertType('non-empty-string', Random::generate($length, 'a-z'));
}


/** @param array<string, mixed> $array */
function testArraysGetKeyOffset(array $array): void
{
	assertType('int<0, max>|null', Arrays::getKeyOffset($array, 'key'));
}


/** @param array<string, string> $array */
function testArraysGrepAndWrap(array $array): void
{
	assertType('array<string, string>', Arrays::grep($array, '#x#'));
	assertType('array<string, string>', Arrays::wrap($array, '<', '>'));
	assertType('array<3|5, string>', Arrays::wrap([3 => 'a', 5 => 'b']));
}


/** @param array<string, mixed> $array */
function testArraysFlatten(array $array, bool $preserveKeys): void
{
	assertType('list<mixed>', Arrays::flatten($array));
	assertType('array<mixed>', Arrays::flatten($array, preserveKeys: true));
	assertType('array<mixed>', Arrays::flatten($array, $preserveKeys));
}


/**
 * @param ArrayHash<int> $hash
 * @param ArrayList<int> $list
 * @param CachingIterator<int, string> $iterator
 */
function testCounts(ArrayHash $hash, ArrayList $list, CachingIterator $iterator): void
{
	assertType('int<0, max>', $hash->count());
	assertType('int<0, max>', $list->count());
	assertType('int<0, max>', $iterator->getCounter());
	assertType('int<0, max>', $iterator->counter);
}
