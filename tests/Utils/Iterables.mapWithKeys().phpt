<?php

/**
 * Test: Nette\Utils\Iterables::mapWithKeys()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('empty iterable', function () {
	$arr = new ArrayIterator([]);
	$log = [];
	$res = Iterables::mapWithKeys(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return [];
		},
	);
	Assert::same([], iterator_to_array($res));
	Assert::same([], $log);
});

test('non-empty iterable', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Iterables::mapWithKeys(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return ["_$k", "_$v"];
		},
	);
	Assert::same(['_x' => '_a', '_y' => '_b'], iterator_to_array($res));
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});

test('skipped elements', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b', 'z' => 'c']);
	$res = Iterables::mapWithKeys(
		$arr,
		fn($v, $k) => $k === 'y' ? null : ["_$k", "_$v"],
	);
	Assert::same(['_x' => '_a', '_z' => '_c'], iterator_to_array($res));
});
