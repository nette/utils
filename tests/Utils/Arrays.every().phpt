<?php

/**
 * Test: Nette\Utils\Arrays::every()
 */

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


(function () {
	$arr = [];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return FALSE; }
	);
	Assert::true($res);
	Assert::same([], $log);
})();

(function () {
	$arr = [];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return TRUE; }
	);
	Assert::true($res);
	Assert::same([], $log);
})();

(function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return FALSE; }
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr]], $log);
})();

(function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return TRUE; }
	);
	Assert::true($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
})();

(function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return $v === 'a'; }
	);
	Assert::false($res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
})();

(function () {
	$arr = ['x' => 'a', 'y' => 'b'];
	$log = [];
	$res = Arrays::every(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return TRUE; }
	);
	Assert::true($res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
})();
