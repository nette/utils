<?php

/**
 * Test: Nette\Utils\Arrays::get()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'' => 'first',
	1 => 'second',
	7 => [
		'item' => 'third',
	],
];

test('Single item', function () use ($arr) {
	Assert::same('first', Arrays::get($arr, ''));
	Assert::same('second', Arrays::get($arr, 1));
	Assert::same('second', Arrays::get($arr, 1, 'x'));
	Assert::same('x', Arrays::get($arr, 'undefined', 'x'));
	Assert::exception(
		fn() => Arrays::get($arr, 'undefined'),
		Nette\InvalidArgumentException::class,
		"Missing item 'undefined'.",
	);
});


test('Traversing', function () use ($arr) {
	Assert::same([
		'' => 'first',
		1 => 'second',
		7 => [
			'item' => 'third',
		],
	], Arrays::get($arr, []));


	Assert::same('third', Arrays::get($arr, [7, 'item']));
});
