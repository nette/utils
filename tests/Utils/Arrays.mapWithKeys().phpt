<?php

/**
 * Test: Nette\Utils\Arrays::mapWithKeys()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('empty array', function () {
	$arr = [];
	$log = [];
	$res = Arrays::mapWithKeys(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return [];
		},
	);
	Assert::same([], $res);
	Assert::same([], $log);
});

test('list', function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::mapWithKeys(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return ["_$k", "_$v"];
		},
	);
	Assert::same(['_0' => '_a', '_1' => '_b'], $res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('array with keys', function () {
	$arr = ['x' => 'a', 'y' => 'b'];
	$log = [];
	$res = Arrays::mapWithKeys(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return ["_$k", "_$v"];
		},
	);
	Assert::same(['_x' => '_a', '_y' => '_b'], $res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
});

test('skipped elements', function () {
	$arr = ['x' => 'a', 'y' => 'b', 'z' => 'c'];
	$res = Arrays::mapWithKeys(
		$arr,
		fn($v, $k) => $k === 'y' ? null : ["_$k", "_$v"],
	);
	Assert::same(['_x' => '_a', '_z' => '_c'], $res);
});
