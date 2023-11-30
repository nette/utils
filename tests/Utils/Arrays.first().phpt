<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('no predicate', function () {
	Assert::null(Arrays::first([]));
	Assert::null(Arrays::first([null]));
	Assert::false(Arrays::first([false]));
	Assert::same(1, Arrays::first([1, 2, 3]));
});

test('internal array pointer is not affected', function () {
	$arr = [1, 2, 3];
	end($arr);
	Assert::same(1, Arrays::first($arr));
	Assert::same(3, current($arr));
});

test('with predicate', function () {
	Assert::null(Arrays::first([], fn() => true));
	Assert::null(Arrays::first([], fn() => false));
	Assert::null(Arrays::first(['' => 'x'], fn() => false));
	Assert::null(Arrays::first([null], fn() => true));
	Assert::null(Arrays::first([null], fn() => false));
	Assert::same(1, Arrays::first([1, 2, 3], fn() => true));
	Assert::null(Arrays::first([1, 2, 3], fn() => false));
	Assert::same(3, Arrays::first([1, 2, 3], fn($v) => $v > 2));
	Assert::same(1, Arrays::first([1, 2, 3], fn($v) => $v < 2));
});

test('predicate arguments', function () {
	Arrays::first([2 => 'x'], fn() => Assert::same(['x', 2, [2 => 'x']], func_get_args()));
});

test('else', function () {
	Assert::same(123, Arrays::first([], else: fn() => 123));
});
