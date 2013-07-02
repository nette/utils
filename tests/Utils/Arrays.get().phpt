<?php

/**
 * Test: Nette\Utils\Arrays::get()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Arrays;


require __DIR__ . '/../bootstrap.php';


$arr  = array(
	NULL => 'first',
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
);

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

	Assert::same( array(
		'' => 'first',
		1 => 'second',
		7 => array(
			'item' => 'third',
		),
	), Arrays::get($arr, array()) );


	Assert::same( 'third', Arrays::get($arr, array(7, 'item')) );
});
