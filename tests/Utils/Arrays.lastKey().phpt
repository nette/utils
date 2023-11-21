<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('no predicate', function () {
	Assert::null(Arrays::lastKey([]));
	Assert::same(0, Arrays::lastKey([null]));
	Assert::same(2, Arrays::lastKey([1, 2, 3]));
	Assert::same(7, Arrays::lastKey([5 => 1, 2, 3]));
});

test('internal array pointer is not affected', function () {
	$arr = [1, 2, 3];
	Assert::same(2, Arrays::lastKey($arr));
	Assert::same(1, current($arr));
});

test('with predicate', function () {
	Assert::null(Arrays::lastKey([], fn() => true));
	Assert::null(Arrays::lastKey([], fn() => false));
	Assert::null(Arrays::lastKey(['' => 'x'], fn() => false));
	Assert::same(0, Arrays::lastKey([null], fn() => true));
	Assert::null(Arrays::lastKey([null], fn() => false));
	Assert::same(2, Arrays::lastKey([1, 2, 3], fn() => true));
	Assert::null(Arrays::lastKey([1, 2, 3], fn() => false));
	Assert::same(2, Arrays::lastKey([1, 2, 3], fn($v) => $v > 2));
	Assert::same(0, Arrays::lastKey([1, 2, 3], fn($v) => $v < 2));
});

test('predicate arguments', function () {
	Arrays::lastKey([2 => 'x'], fn() => Assert::same(['x', 2, [2 => 'x']], func_get_args()));
});
