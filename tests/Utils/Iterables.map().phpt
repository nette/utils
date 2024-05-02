<?php

/**
 * Test: Nette\Utils\Iterables::map()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('empty iterable', function () {
	$arr = new ArrayIterator([]);
	$log = [];
	$res = Iterables::map(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::same([], iterator_to_array($res));
	Assert::same([], $log);
});

test('non-empty iterable', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Iterables::map(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v . $v;
		},
	);
	Assert::same(['x' => 'aa', 'y' => 'bb'], iterator_to_array($res));
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});
