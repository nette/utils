<?php

/**
 * Test: Nette\ArrayTools::get()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../initialize.php';



$arr  = array(
	NULL => 'first',
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
);

// Single item

Assert::same( 'first', ArrayTools::get($arr, NULL) );
Assert::same( 'second', ArrayTools::get($arr, 1) );
Assert::same( 'second', ArrayTools::get($arr, 1, 'x') );
Assert::same( 'x', ArrayTools::get($arr, 'undefined', 'x') );
Assert::null( ArrayTools::get($arr, 'undefined') );



// Traversing

Assert::same( array(
	'' => 'first',
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
), ArrayTools::get($arr, array()) );


Assert::same( 'third', ArrayTools::get($arr, array(7, 'item')) );
