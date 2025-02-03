<?php

/**
 * Test: Nette\Utils\Arrays::pick()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'' => 'null',
	1 => 'first',
	2 => 'second',
];

test('single item', function () use ($arr) {
	Assert::same('null', Arrays::pick($arr, ''));
	Assert::same('first', Arrays::pick($arr, 1));
	Assert::same('x', Arrays::pick($arr, 1, 'x'));
	Assert::exception(
		fn() => Arrays::pick($arr, 'undefined'),
		Nette\InvalidArgumentException::class,
		"Missing item 'undefined'.",
	);
	Assert::same([2 => 'second'], $arr);
});
