<?php

/**
 * Test: Nette\Utils\Iterables::every()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('empty iterable bypasses callback with false return', function () {
	$arr = new ArrayIterator([]);
	$log = [];
	$res = Iterables::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::true($res);
	Assert::same([], $log);
});

test('empty iterable bypasses callback with true return', function () {
	$arr = new ArrayIterator([]);
	$log = [];
	$res = Iterables::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([], $log);
});

test('stops iteration on first false value', function () {
	$arr = new ArrayIterator(['a', 'b']);
	$log = [];
	$res = Iterables::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr]], $log);
});

test('processes all elements returning true', function () {
	$arr = new ArrayIterator(['a', 'b']);
	$log = [];
	$res = Iterables::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('terminates when element does not match condition', function () {
	$arr = new ArrayIterator(['a', 'b']);
	$log = [];
	$res = Iterables::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('iterates associative iterable with all true results', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Iterables::every(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});
