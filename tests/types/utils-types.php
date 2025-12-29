<?php

/**
 * PHPStan type tests for Utils.
 * Run: vendor/bin/phpstan analyse tests/types
 */

declare(strict_types=1);

use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays;
use Nette\Utils\Html;
use Nette\Utils\Strings;
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
		return $value > 0;
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


function testStringsSplit(): void
{
	$withoutOffset = Strings::split('a,b', '#(,)#');
	assertType('list<string>', $withoutOffset);

	$withOffset = Strings::split('a,b', '#(,)#', captureOffset: true);
	assertType('list<array{string, int}>', $withOffset);
}


function testStringsMatch(): void
{
	$withoutOffset = Strings::match('hello', '#l+#');
	assertType('array<string|null>|null', $withoutOffset);

	$withOffset = Strings::match('hello', '#l+#', captureOffset: true);
	assertType('array<array{string, int}|null>|null', $withOffset);
}


function testStringsMatchAll(): void
{
	$withoutOffset = Strings::matchAll('hello', '#l+#');
	assertType('list<array<string|null>>', $withoutOffset);

	$withOffset = Strings::matchAll('hello', '#l+#', captureOffset: true);
	assertType('list<array<array{string, int}|null>>', $withOffset);

	$lazy = Strings::matchAll('hello', '#l+#', lazy: true);
	assertType('Generator<int, array<string|null>, mixed, mixed>', $lazy);

	$lazyWithOffset = Strings::matchAll('hello', '#l+#', captureOffset: true, lazy: true);
	assertType('Generator<int, array<array{string, int}|null>, mixed, mixed>', $lazyWithOffset);
}
