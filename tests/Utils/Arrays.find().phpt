<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$arr = [];
	$log = [];
	$res = Arrays::find(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::same(null, $res);
	Assert::same([], $log);
});

test('', function () {
	$arr = ['a', 'b', 'c'];
	$log = [];
	$res = Arrays::find(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return $v === 'b';
		},
	);
	Assert::same('b', $res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
});

test('', function () {
	$arr = ['a', 'b', 'c'];
	$log = [];
	$res = Arrays::find(
		$arr,
		function ($v, $k, $arr) use (&$log) {
			$log[] = func_get_args();
			return false;
		},
	);
	Assert::same(null, $res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr], ['c', 2, $arr]], $log);
});
