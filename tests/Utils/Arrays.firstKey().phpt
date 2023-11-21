<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('no predicate', function () {
	Assert::null(Arrays::firstKey([]));
	Assert::same(0, Arrays::firstKey([null]));
	Assert::same(0, Arrays::firstKey([1, 2, 3]));
	Assert::same(5, Arrays::firstKey([5 => 1, 2, 3]));
});

test('internal array pointer is not affected', function () {
	$arr = [1, 2, 3];
	end($arr);
	Assert::same(0, Arrays::firstKey($arr));
	Assert::same(3, current($arr));
});

test('with predicate', function () {
	Assert::null(Arrays::firstKey([], fn() => true));
	Assert::null(Arrays::firstKey([], fn() => false));
	Assert::null(Arrays::firstKey(['' => 'x'], fn() => false));
	Assert::same(0, Arrays::firstKey([null], fn() => true));
	Assert::null(Arrays::firstKey([null], fn() => false));
	Assert::same(0, Arrays::firstKey([1, 2, 3], fn() => true));
	Assert::null(Arrays::firstKey([1, 2, 3], fn() => false));
	Assert::same(2, Arrays::firstKey([1, 2, 3], fn($v) => $v > 2));
	Assert::same(0, Arrays::firstKey([1, 2, 3], fn($v) => $v < 2));
});

test('predicate arguments', function () {
	Arrays::firstKey([2 => 'x'], fn() => Assert::same(['x', 2, [2 => 'x']], func_get_args()));
});
