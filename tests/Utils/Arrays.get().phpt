<?php

/**
 * Test: Nette\Utils\Arrays::get()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
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

// Single item

Assert::same( 'first', Arrays::get($arr, NULL) );
Assert::same( 'second', Arrays::get($arr, 1) );
Assert::same( 'second', Arrays::get($arr, 1, 'x') );
Assert::same( 'x', Arrays::get($arr, 'undefined', 'x') );
Assert::throws(function() use ($arr) {
	Arrays::get($arr, 'undefined');
}, 'Nette\InvalidArgumentException', "Missing item 'undefined'.");



// Traversing

Assert::same( array(
	'' => 'first',
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
), Arrays::get($arr, array()) );


Assert::same( 'third', Arrays::get($arr, array(7, 'item')) );
