<?php

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('no predicate', function () {
	Assert::null(Iterables::first(new ArrayIterator([])));
	Assert::null(Iterables::first(new ArrayIterator([null])));
	Assert::false(Iterables::first(new ArrayIterator([false])));
	Assert::same(1, Iterables::first(new ArrayIterator([1, 2, 3])));
});

test('internal array pointer is not affected', function () {
	$arr = [1, 2, 3];
	end($arr);
	Assert::same(1, Iterables::first($arr));
	Assert::same(3, current($arr));
});

test('with predicate', function () {
	Assert::null(Iterables::first([], fn() => true));
	Assert::null(Iterables::first([], fn() => false));
	Assert::null(Iterables::first(['' => 'x'], fn() => false));
	Assert::null(Iterables::first([null], fn() => true));
	Assert::null(Iterables::first([null], fn() => false));
	Assert::same(1, Iterables::first([1, 2, 3], fn() => true));
	Assert::null(Iterables::first([1, 2, 3], fn() => false));
	Assert::same(3, Iterables::first([1, 2, 3], fn($v) => $v > 2));
	Assert::same(1, Iterables::first([1, 2, 3], fn($v) => $v < 2));
});

test('predicate arguments', function () {
	Iterables::first([2 => 'x'], fn() => Assert::same(['x', 2, [2 => 'x']], func_get_args()));
});

test('else', function () {
	Assert::same(123, Iterables::first(new ArrayIterator([]), else: fn() => 123));
});
