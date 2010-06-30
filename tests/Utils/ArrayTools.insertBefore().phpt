<?php

/**
 * Test: Nette\ArrayTools::insertBefore() & insertAfter()
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
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

T::dump( $arr );

T::note('First item');
$dolly = $arr;
ArrayTools::insertBefore($dolly, NULL, array('new' => 'value'));
T::dump( $dolly );

$dolly = $arr;
ArrayTools::insertAfter($dolly, NULL, array('new' => 'value'));
T::dump( $dolly );


T::note('Last item');
$dolly = $arr;
ArrayTools::insertBefore($dolly, 7, array('new' => 'value'));
T::dump( $dolly );

$dolly = $arr;
ArrayTools::insertAfter($dolly, 7, array('new' => 'value'));
T::dump( $dolly );


T::note('Undefined item');
$dolly = $arr;
ArrayTools::insertBefore($dolly, 'undefined', array('new' => 'value'));
T::dump( $dolly );

$dolly = $arr;
ArrayTools::insertAfter($dolly, 'undefined', array('new' => 'value'));
T::dump( $dolly );



__halt_compiler() ?>

------EXPECT------
array(
	"" => "first"
	0 => "second"
	1 => "third"
	7 => "fourth"
)

First item

array(
	"new" => "value"
	"" => "first"
	0 => "second"
	1 => "third"
	7 => "fourth"
)

array(
	"" => "first"
	"new" => "value"
	0 => "second"
	1 => "third"
	7 => "fourth"
)

Last item

array(
	"" => "first"
	0 => "second"
	1 => "third"
	"new" => "value"
	7 => "fourth"
)

array(
	"" => "first"
	0 => "second"
	1 => "third"
	7 => "fourth"
	"new" => "value"
)

Undefined item

array(
	"new" => "value"
	"" => "first"
	0 => "second"
	1 => "third"
	7 => "fourth"
)

array(
	"" => "first"
	0 => "second"
	1 => "third"
	7 => "fourth"
	"new" => "value"
)
