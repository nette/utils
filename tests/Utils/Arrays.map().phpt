<?php

/**
 * Test: Nette\Utils\Arrays::map()
 */

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


(function () {
	$arr = [];
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return TRUE; }
	);
	Assert::same([], $res);
	Assert::same([], $log);
})();

(function () {
	$arr = ['a', 'b'];
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return $v . $v; }
	);
	Assert::same(['aa', 'bb'], $res);
	Assert::same([['a', 0, $arr], ['b', 1, $arr]], $log);
})();

(function () {
	$arr = ['x' => 'a', 'y' => 'b'];
	$log = [];
	$res = Arrays::map(
		$arr,
		function ($v, $k, $arr) use (&$log) { $log[] = func_get_args(); return $v . $v; }
	);
	Assert::same(['x' => 'aa', 'y' => 'bb'], $res);
	Assert::same([['a', 'x', $arr], ['b', 'y', $arr]], $log);
})();
