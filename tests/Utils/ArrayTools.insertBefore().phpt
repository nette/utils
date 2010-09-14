<?php

/**
 * Test: Nette\ArrayTools::insertBefore() & insertAfter()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../initialize.php';



$arr  = array(
	NULL => 'first',
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

Assert::same( array(
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
), $arr );


// First item
$dolly = $arr;
ArrayTools::insertBefore($dolly, NULL, array('new' => 'value'));
Assert::same( array(
	'new' => 'value',
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
), $dolly );


$dolly = $arr;
ArrayTools::insertAfter($dolly, NULL, array('new' => 'value'));
Assert::same( array(
	'' => 'first',
	'new' => 'value',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
), $dolly );



// Last item
$dolly = $arr;
ArrayTools::insertBefore($dolly, 7, array('new' => 'value'));
Assert::same( array(
	'' => 'first',
	0 => 'second',
	1 => 'third',
	'new' => 'value',
	7 => 'fourth',
), $dolly );


$dolly = $arr;
ArrayTools::insertAfter($dolly, 7, array('new' => 'value'));
Assert::same( array(
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
	'new' => 'value',
), $dolly );



// Undefined item
$dolly = $arr;
ArrayTools::insertBefore($dolly, 'undefined', array('new' => 'value'));
Assert::same( array(
	'new' => 'value',
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
), $dolly );


$dolly = $arr;
ArrayTools::insertAfter($dolly, 'undefined', array('new' => 'value'));
Assert::same( array(
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
	'new' => 'value',
), $dolly );
