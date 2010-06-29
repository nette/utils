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

string(5) "first"

string(6) "second"

string(6) "second"

string(1) "x"

NULL

Traversing

array(3) {
	"" => string(5) "first"
	1 => string(6) "second"
	7 => array(1) {
		"item" => string(5) "third"
	}
}

string(5) "third"
