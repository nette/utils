<?php

/**
 * Test: Nette\ArrayUtils::get()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayUtils;



require __DIR__ . '/../bootstrap.php';



$arr  = array(
	NULL => 'first',
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
);

// Single item

Assert::same( 'first', ArrayUtils::get($arr, NULL) );
Assert::same( 'second', ArrayUtils::get($arr, 1) );
Assert::same( 'second', ArrayUtils::get($arr, 1, 'x') );
Assert::same( 'x', ArrayUtils::get($arr, 'undefined', 'x') );
Assert::null( ArrayUtils::get($arr, 'undefined') );



// Traversing

Assert::same( array(
	'' => 'first',
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
), ArrayUtils::get($arr, array()) );


Assert::same( 'third', ArrayUtils::get($arr, array(7, 'item')) );
