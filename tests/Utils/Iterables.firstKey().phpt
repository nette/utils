<?php

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('no predicate', function () {
	Assert::null(Iterables::firstKey(new ArrayIterator([])));
	Assert::same(0, Iterables::firstKey(new ArrayIterator([null])));
	Assert::same(0, Iterables::firstKey(new ArrayIterator([1, 2, 3])));
	Assert::same(5, Iterables::firstKey(new ArrayIterator([5 => 1, 2, 3])));
});

test('internal array pointer is not affected', function () {
	$arr = [1, 2, 3];
	end($arr);
	Assert::same(0, Iterables::firstKey($arr));
	Assert::same(3, current($arr));
});

test('with predicate', function () {
	Assert::null(Iterables::firstKey([], fn() => true));
	Assert::null(Iterables::firstKey([], fn() => false));
	Assert::null(Iterables::firstKey(['' => 'x'], fn() => false));
	Assert::same(0, Iterables::firstKey([null], fn() => true));
	Assert::null(Iterables::firstKey([null], fn() => false));
	Assert::same(0, Iterables::firstKey([1, 2, 3], fn() => true));
	Assert::null(Iterables::firstKey([1, 2, 3], fn() => false));
	Assert::same(2, Iterables::firstKey([1, 2, 3], fn($v) => $v > 2));
	Assert::same(0, Iterables::firstKey([1, 2, 3], fn($v) => $v < 2));
});

test('predicate arguments', function () {
	Iterables::firstKey([2 => 'x'], fn() => Assert::same(['x', 2, [2 => 'x']], func_get_args()));
});

test('else', function () {
	Assert::same(123, Iterables::firstKey(new ArrayIterator([]), else: fn() => 123));
});
