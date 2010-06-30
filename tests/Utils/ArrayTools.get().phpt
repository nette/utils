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

T::note('Single item');

T::dump( ArrayTools::get($arr, NULL) );

T::dump( ArrayTools::get($arr, 1) );

T::dump( ArrayTools::get($arr, 1, 'x') );

T::dump( ArrayTools::get($arr, 'undefined', 'x') );

T::dump( ArrayTools::get($arr, 'undefined') );


T::note('Traversing');

T::dump( ArrayTools::get($arr, array()) );

T::dump( ArrayTools::get($arr, array(7, 'item')) );



__halt_compiler() ?>

------EXPECT------
Single item

"first"

"second"

"second"

"x"

NULL

Traversing

array(
	"" => "first"
	1 => "second"
	7 => array(
		"item" => "third"
	)
)

"third"
