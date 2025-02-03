<?php

/**
 * Test: Nette\Utils\Arrays::every()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('empty array returns true without iteration', function () {
	$arr = [];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::true($res);
	Assert::same([], $log);
});

test('empty array returns true regardless of callback result', function () {
	$arr = [];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([], $log);
});

test('iteration stops on first false predicate', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr]], $log);
});

test('all elements satisfying predicate return true', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('not all elements satisfying predicate return false', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('associative array iteration preserves key order in callback', function () {
	$arr = ['x' => 'a', 'y' => 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});

test('works seamlessly with Traversable objects', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});
