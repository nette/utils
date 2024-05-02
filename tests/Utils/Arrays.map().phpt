<?php

/**
 * Test: Nette\Utils\Arrays::map()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('empty array', function () {
	$arr = [];
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::same([], $res);
	Assert::same([], $log);
});

test('list', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v . $v;
		},
	);
	Assert::same(['aa', 'bb'], $res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('array with keys', function () {
	$arr = ['x' => 'a', 'y' => 'b'];
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v . $v;
		},
	);
	Assert::same(['x' => 'aa', 'y' => 'bb'], $res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});

test('iterator', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v . $v;
		},
	);
	Assert::same(['x' => 'aa', 'y' => 'bb'], $res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});
