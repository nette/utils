<?php

/**
 * Test: Nette\Utils\Arrays::pick()
 */

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	NULL => 'null', // NULL is ''
	1 => 'first',
	2 => 'second',
];

test(function () use ($arr) { // Single item

	Assert::same('null', Arrays::pick($arr, NULL));
	Assert::same('first', Arrays::pick($arr, 1));
	Assert::same('x', Arrays::pick($arr, 1, 'x'));
	Assert::exception(function () use ($arr) {
		Arrays::get($arr, 'undefined');
	}, 'Nette\InvalidArgumentException', "Missing item 'undefined'.");
	Assert::same([2 => 'second'], $arr);
});
