<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('no predicate', function () {
	Assert::null(Arrays::last([]));
	Assert::null(Arrays::last([null]));
	Assert::false(Arrays::last([false]));
	Assert::same(3, Arrays::last([1, 2, 3]));
});

test('internal array pointer is not affected', function () {
	$arr = [1, 2, 3];
	Assert::same(3, Arrays::last($arr));
	Assert::same(1, current($arr));
});

test('with predicate', function () {
	Assert::null(Arrays::last([], fn() => true));
	Assert::null(Arrays::last([], fn() => false));
	Assert::null(Arrays::last(['' => 'x'], fn() => false));
	Assert::null(Arrays::last([null], fn() => true));
	Assert::null(Arrays::last([null], fn() => false));
	Assert::same(3, Arrays::last([1, 2, 3], fn() => true));
	Assert::null(Arrays::last([1, 2, 3], fn() => false));
	Assert::same(3, Arrays::last([1, 2, 3], fn($v) => $v > 2));
	Assert::same(1, Arrays::last([1, 2, 3], fn($v) => $v < 2));
});

test('predicate arguments', function () {
	Arrays::last([2 => 'x'], fn() => Assert::same(['x', 2, [2 => 'x']], func_get_args()));
});

test('else', function () {
	Assert::same(123, Arrays::last([], else: fn() => 123));
});
