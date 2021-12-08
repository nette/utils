<?php

/**
 * Test: Nette\Utils\Validators::is()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	Assert::false(Validators::is(true, 'int'));
	Assert::false(Validators::is('1', 'int'));
	Assert::true(Validators::is(1, 'integer'));
	Assert::true(Validators::is(1, 'int'));
	Assert::false(Validators::is(1, 'int:)'));
	Assert::false(Validators::is(1, 'int:0'));
	Assert::true(Validators::is(1, 'int:1'));
	Assert::true(Validators::is(0, 'int:0'));
	Assert::true(Validators::is(1, 'int:..'));
	Assert::false(Validators::is(1, 'int:0..0'));
	Assert::true(Validators::is(1, 'int:0..1'));
	Assert::true(Validators::is(1, 'int:0..'));
	Assert::false(Validators::is(1, 'int:..0'));
	Assert::true(Validators::is(1, 'int:..1'));
	Assert::true(Validators::is(0, 'int:..0'));
	Assert::true(Validators::is(-1, 'int:..0'));
});


test('', function () {
	Assert::false(Validators::is(true, 'float'));
	Assert::false(Validators::is('1', 'float'));
	Assert::false(Validators::is(1, 'float'));
	Assert::true(Validators::is(1.0, 'float'));
});


test('', function () {
	Assert::false(Validators::is(true, 'number'));
	Assert::false(Validators::is('1', 'number'));
	Assert::true(Validators::is(1, 'number'));
	Assert::true(Validators::is(1.0, 'number'));
});


test('', function () {
	Assert::false(Validators::is(true, 'numeric'));
	Assert::true(Validators::is('1', 'numeric'));
	Assert::true(Validators::is('-1', 'numeric'));
	Assert::true(Validators::is('-1.5', 'numeric'));
	Assert::true(Validators::is('-.5', 'numeric'));
	Assert::true(Validators::is('+1', 'numeric'));
	Assert::true(Validators::is('+1.5', 'numeric'));
	Assert::true(Validators::is('+.5', 'numeric'));
	Assert::false(Validators::is('1e6', 'numeric'));
	Assert::true(Validators::is(1, 'numeric'));
	Assert::true(Validators::is(1.0, 'numeric'));
});


test('', function () {
	Assert::false(Validators::is(true, 'numericint'));
	Assert::true(Validators::is('1', 'numericint'));
	Assert::true(Validators::is('-1', 'numericint'));
	Assert::false(Validators::is('-1.5', 'numericint'));
	Assert::false(Validators::is('-.5', 'numericint'));
	Assert::true(Validators::is('+1', 'numericint'));
	Assert::false(Validators::is('+1.5', 'numericint'));
	Assert::false(Validators::is('+.5', 'numericint'));
	Assert::false(Validators::is('1e6', 'numericint'));
	Assert::true(Validators::is(1, 'numericint'));
	Assert::false(Validators::is(1.0, 'numericint'));
});


test('', function () {
	Assert::false(Validators::is(1, 'bool'));
	Assert::true(Validators::is(true, 'bool'));
	Assert::true(Validators::is(false, 'bool'));
	Assert::true(Validators::is(true, 'boolean'));
	Assert::true(Validators::is(true, 'bool:1'));
	Assert::false(Validators::is(true, 'bool:0'));
	Assert::false(Validators::is(false, 'bool:1'));
	Assert::true(Validators::is(false, 'bool:0'));
	Assert::true(Validators::is(false, 'bool:0..1'));
});


test('', function () {
	Assert::false(Validators::is(1, 'string'));
	Assert::true(Validators::is('', 'string'));
	Assert::true(Validators::is('hello', 'string'));
	Assert::true(Validators::is('hello', 'string:5'));
	Assert::false(Validators::is('hello', 'string:4'));
	Assert::true(Validators::is('hello', 'string:4..'));
	Assert::false(Validators::is('hello', 'string:1..4'));
});


test('', function () {
	Assert::false(Validators::is(1, 'unicode'));
	Assert::true(Validators::is('', 'unicode'));
	Assert::true(Validators::is('hello', 'unicode'));
	Assert::false(Validators::is("hello\xFF", 'unicode'));
	Assert::true(Validators::is('hello', 'unicode:5'));
	Assert::false(Validators::is('hello', 'unicode:4'));
	Assert::true(Validators::is('hello', 'unicode:4..'));
	Assert::false(Validators::is('hello', 'unicode:1..4'));
});


test('', function () {
	Assert::false(Validators::is(null, 'array'));
	Assert::true(Validators::is([], 'array'));
	Assert::true(Validators::is([], 'array:0'));
	Assert::true(Validators::is([1], 'array:1'));
	Assert::true(Validators::is([1], 'array:0..'));
	Assert::true(Validators::is([], 'array:..1'));
});


test('', function () {
	Assert::false(Validators::is(null, 'list'));
	Assert::true(Validators::is([], 'list'));
	Assert::true(Validators::is([1], 'list'));
	Assert::true(Validators::is(['a', 'b', 'c'], 'list'));
	Assert::false(Validators::is([4 => 1, 2, 3], 'list'));
	Assert::false(Validators::is([1 => 'a', 0 => 'b'], 'list'));
	Assert::false(Validators::is(['key' => 'value'], 'list'));
	$arr = [];
	$arr[] = &$arr;
	Assert::true(Validators::is($arr, 'list'));
	Assert::false(Validators::is([1, 2, 3], 'list:4'));
});


test('', function () {
	Assert::false(Validators::is(null, 'object'));
	Assert::true(Validators::is(new stdClass, 'object'));
});


test('', function () {
	Assert::false(Validators::is(null, 'scalar'));
	Assert::false(Validators::is([], 'scalar'));
	Assert::true(Validators::is(1, 'scalar'));
});


test('', function () {
	Assert::false(Validators::is(null, 'callable'));
	Assert::false(Validators::is([], 'callable'));
	Assert::false(Validators::is(1, 'callable'));
	Assert::false(Validators::is('', 'callable'));
	Assert::true(Validators::is('hello', 'callable'));
	Assert::false(Validators::is(['hello'], 'callable'));
	Assert::true(Validators::is(['hello', 'world'], 'callable'));
});


test('', function () {
	Assert::false(Validators::is(0, 'null'));
	Assert::true(Validators::is(null, 'null'));
});


test('', function () {
	Assert::true(Validators::is([], 'mixed'));
	Assert::true(Validators::is(null, 'mixed'));
});


test('', function () {
	Assert::false(Validators::is('', 'email'));
	Assert::false(Validators::is(false, 'email'));
	Assert::false(Validators::is('hello', 'email'));
	Assert::true(Validators::is('hello@world.cz', 'email'));
	Assert::false(Validators::is('hello@localhost', 'email'));
	Assert::false(Validators::is('hello@127.0.0.1', 'email'));
	Assert::false(Validators::is('hello@localhost.a0', 'email'));
	Assert::false(Validators::is('hello@localhost.0a', 'email'));
	Assert::true(Validators::is('hello@l.org', 'email'));
	Assert::true(Validators::is('hello@1.org', 'email'));
	Assert::false(Validators::is('jean.françois@lyotard.fr', 'email'));
	Assert::true(Validators::is('jerzy@kosiński.pl', 'email'));
	Assert::false(Validators::is('péter@esterházy.hu', 'email'));
	Assert::true(Validators::is('hello@1.c0m', 'email'));
	Assert::true(Validators::is('hello@1.c', 'email'));
});


test('', function () {
	Assert::false(Validators::is('', 'url'));
	Assert::false(Validators::is(false, 'url'));
	Assert::false(Validators::is('hello', 'url'));
	Assert::false(Validators::is('nette.org', 'url'));
	Assert::false(Validators::is('http://nette.org0', 'url'));
	Assert::false(Validators::is('http://nette.0org', 'url'));
	Assert::false(Validators::is('http://_nette.org', 'url'));
	Assert::false(Validators::is('http://www._nette.org', 'url'));
	Assert::false(Validators::is('http://www.ne_tte.org', 'url'));
	Assert::true(Validators::is('http://1.org', 'url'));
	Assert::true(Validators::is('http://l.org', 'url'));
	Assert::true(Validators::is('http://localhost', 'url'));
	Assert::true(Validators::is('http://127.0.0.1', 'url'));
	Assert::true(Validators::is('http://[::1]', 'url'));
	Assert::true(Validators::is('http://[2001:0db8:0000:0000:0000:0000:1428:57AB]', 'url'));
	Assert::true(Validators::is('http://nette.org/path', 'url'));
	Assert::true(Validators::is('http://nette.org:8080/path', 'url'));
	Assert::true(Validators::is('https://www.nette.org/path', 'url'));
	Assert::true(Validators::is('https://www.nette.org/path?query#fragment', 'url'));
	Assert::true(Validators::is('https://www.nette.org?query', 'url'));
	Assert::true(Validators::is('https://www.nette.org#fragment', 'url'));
	Assert::true(Validators::is('https://www.nette.org?#', 'url'));
	Assert::true(Validators::is('https://example.c0m', 'url'));
	Assert::true(Validators::is('https://example.l', 'url'));
	Assert::true(Validators::is('http://one_two.example.com', 'url'));
	Assert::true(Validators::is('http://_.example.com', 'url'));
	Assert::true(Validators::is('http://_e_.example.com', 'url'));
});


test('', function () {
	Assert::false(Validators::is('', 'uri'));
	Assert::false(Validators::is(false, 'uri'));
	Assert::false(Validators::is('hello', 'uri'));
	Assert::false(Validators::is('nette.org', 'uri'));
	Assert::false(Validators::is('mailto: gandalf@example.org', 'uri'));
	Assert::false(Validators::is('invalid-scheme :gandalf@example.org', 'uri'));
	Assert::false(Validators::is('invalid-scheme~:gandalf@example.org', 'uri'));
	Assert::true(Validators::is('mailto:gandalf@example.org', 'uri'));
	Assert::true(Validators::is('valid-scheme+.0:lalala', 'uri'));
	Assert::true(Validators::is('bitcoin:mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn', 'uri'));
});


test('', function () {
	Assert::true(Validators::is(0, 'none'));
	Assert::true(Validators::is('', 'none'));
	Assert::true(Validators::is(null, 'none'));
	Assert::true(Validators::is(false, 'none'));
	Assert::false(Validators::is('0', 'none'));
	Assert::true(Validators::is([], 'none'));
});


test('', function () {
	Assert::true(Validators::is('', 'pattern'));
	Assert::true(Validators::is('  123', 'pattern:\s+\d+'));
	Assert::false(Validators::is('  123x', 'pattern:\s+\d+'));
});


test('', function () {
	Assert::false(Validators::is('', 'alnum'));
	Assert::false(Validators::is('a-1', 'alnum'));
	Assert::true(Validators::is('a1', 'alnum'));
	Assert::true(Validators::is('a1', 'alnum:2'));
});


test('', function () {
	Assert::false(Validators::is('', 'alpha'));
	Assert::false(Validators::is('a1', 'alpha'));
	Assert::true(Validators::is('aA', 'alpha'));
	Assert::true(Validators::is('aA', 'alpha:1..3'));
});


test('', function () {
	Assert::false(Validators::is('', 'digit'));
	Assert::false(Validators::is('123x', 'digit'));
	Assert::true(Validators::is('123', 'digit'));
	Assert::false(Validators::is('123', 'digit:..2'));
});


test('', function () {
	Assert::false(Validators::is('', 'lower'));
	Assert::false(Validators::is('Hello', 'lower'));
	Assert::true(Validators::is('hello', 'lower'));
	Assert::false(Validators::is('hello', 'lower:9'));
});


test('', function () {
	Assert::false(Validators::is('', 'upper'));
	Assert::false(Validators::is('Hello', 'upper'));
	Assert::true(Validators::is('HELLO', 'upper'));
});


test('', function () {
	Assert::false(Validators::is('', 'space'));
	Assert::false(Validators::is(' 1', 'space'));
	Assert::true(Validators::is(" \t\r\n", 'space'));
});


test('', function () {
	Assert::false(Validators::is('', 'xdigit'));
	Assert::false(Validators::is('123x', 'xdigit'));
	Assert::true(Validators::is('123aA', 'xdigit'));
});


test('', function () {
	Assert::true(Validators::is(1.0, 'int|float'));
	Assert::true(Validators::is(1, 'int|float'));
	Assert::false(Validators::is('1', 'int|float'));
});


test('', function () {
	class rimmer
	{
	}
	interface kryton
	{
	}

	Assert::true(Validators::is('rimmer', 'type'));
	Assert::true(Validators::is('kryton', 'type'));
	Assert::false(Validators::is('1', 'type'));
});


test('', function () {
	Assert::true(Validators::is('rimmer', 'class'));
	Assert::false(Validators::is('kryton', 'class'));
	Assert::false(Validators::is('1', 'class'));
});


test('', function () {
	Assert::false(Validators::is('rimmer', 'interface'));
	Assert::true(Validators::is('kryton', 'interface'));
	Assert::false(Validators::is('1', 'interface'));
});


test('', function () {
	Assert::true(Validators::is(__FILE__, 'file'));
	Assert::false(Validators::is(__FILE__ . 'xx', 'class'));
});


test('', function () {
	Assert::true(Validators::is(__DIR__, 'directory'));
	Assert::false(Validators::is(__DIR__ . 'xx', 'directory'));
});


test('', function () {
	Assert::true(Validators::is('Item', 'identifier'));
	Assert::false(Validators::is('0Item', 'identifier'));
});

test('', function () {
	Assert::true(Validators::is('', 'string:0|email'));
	Assert::true(Validators::is('foo@bar.com', 'string:0|email'));
	Assert::false(Validators::is('foo', 'string:0|email'));
});


test('', function () {
	$gen = function () {
		yield;
	};

	Assert::true(Validators::is([1, 2, 3], 'iterable'));
	Assert::true(Validators::is(new ArrayIterator([1, 2, 3]), 'iterable'));
	Assert::true(Validators::is($gen(), 'iterable'));
	Assert::false(Validators::is(1, 'iterable'));
	Assert::false(Validators::is(3.14, 'iterable'));
	Assert::false(Validators::is(new stdClass, 'iterable'));
});


test('', function () {
	class Abc
	{
	}

	Assert::true(Validators::is([], 'int[]'));
	Assert::true(Validators::is(new ArrayIterator([]), 'int[]'));
	Assert::false(Validators::is(1, 'int[]'));
	Assert::false(Validators::is(2.15, 'int[]'));
	Assert::true(Validators::is(2.15, 'float|int[]'));
	Assert::true(Validators::is(2.15, 'int[]|float'));
	Assert::true(Validators::is([1, 2, 3], 'int[]'));
	Assert::false(Validators::is([1, 2, 3], 'int[][]'));
	Assert::true(Validators::is([[1], [2, 3]], 'int[][]'));
	Assert::false(Validators::is([1, 2.15, 3], 'int[]'));
	Assert::true(Validators::is([1, 2.15, 3], 'number[]'));

	Assert::true(Validators::is([new Abc], 'Abc[]'));
	Assert::false(Validators::is([new Abc, new stdClass], 'Abc[]'));

	Assert::true(Validators::is(['ABCD', 'EFGH', 'IJKL'], 'string:4[]'));
	Assert::false(Validators::is(['ABCD', 'EFGH', 'IJKLM'], 'string:4[]'));

	Assert::true(Validators::is([['ABCD', 'EFGH'], ['IJKL']], 'string:4[][]'));
	Assert::false(Validators::is([['ABCD', 'EFGH'], ['IJKLM']], 'string:4[][]'));
});


test('', function () {
	Assert::true(Validators::is(null, '?string'));
	Assert::true(Validators::is('1', '?string'));
	Assert::false(Validators::is(true, '?int'));
	Assert::false(Validators::is(0, '?string'));
});
