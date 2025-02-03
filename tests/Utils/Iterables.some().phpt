<?php

/**
 * Test: Nette\Utils\Iterables::some()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('empty iterable returns false without callback execution', function () {
	$arr = new ArrayIterator([]);
	$log = [];
	$res = Iterables::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::false($res);
	Assert::same([], $log);
});

test('empty iterable ignores callback despite true return', function () {
	$arr = new ArrayIterator([]);
	$log = [];
	$res = Iterables::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::false($res);
	Assert::same([], $log);
});

test('iterates all elements when none match', function () {
	$arr = new ArrayIterator(['a', 'b']);
	$log = [];
	$res = Iterables::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('halts iteration on first true value', function () {
	$arr = new ArrayIterator(['a', 'b']);
	$log = [];
	$res = Iterables::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return true;
		},
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr]], $log);
});

test('stops on first element matching condition', function () {
	$arr = new ArrayIterator(['a', 'b']);
	$log = [];
	$res = Iterables::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr]], $log);
});

test('processes associative iterable and stops on match', function () {
	$arr = new ArrayIterator(['x' => 'a', 'y' => 'b']);
	$log = [];
	$res = Iterables::some(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'a';
		},
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr]], $log);
});
