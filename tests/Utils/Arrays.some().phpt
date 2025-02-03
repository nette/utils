<?php

/**
 * Test: Nette\Utils\Arrays::some()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('empty array returns false without iteration', function () {
	$arr = [];
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::false($res);
	Assert::same([], $log);
});

test('empty array always fails some condition', function () {
	$arr = [];
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::false($res);
	Assert::same([], $log);
});

test('all elements failing predicate result in false', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('iteration stops on first true predicate', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr]], $log);
});

test('some element satisfying predicate returns true', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr]], $log);
});

test('works with associative arrays to identify a matching element', function () {
	$arr = ['x' => 'a', 'y' => 'b'];
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr]], $log);
});

test('works with Traversable objects in some method', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Arrays::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr]], $log);
});
