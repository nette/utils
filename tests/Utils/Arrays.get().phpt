<?php

/**
 * Test: Nette\Utils\Arrays::get()
 */

use Nette\Utils\Arrays,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr  = [
	NULL => 'first',
	1 => 'second',
	7 => [
		'item' => 'third',
	],
];

test(function() use ($arr) { // Single item

	Assert::same( 'first', Arrays::get($arr, NULL) );
	Assert::same( 'second', Arrays::get($arr, 1) );
	Assert::same( 'second', Arrays::get($arr, 1, 'x') );
	Assert::same( 'x', Arrays::get($arr, 'undefined', 'x') );
	Assert::exception(function() use ($arr) {
		Arrays::get($arr, 'undefined');
	}, 'Nette\InvalidArgumentException', "Missing item 'undefined'.");
});


test(function() use ($arr) { // Traversing

	Assert::same( [
		'' => 'first',
		1 => 'second',
		7 => [
			'item' => 'third',
		],
	], Arrays::get($arr, []) );


	Assert::same( 'third', Arrays::get($arr, [7, 'item']) );
});
