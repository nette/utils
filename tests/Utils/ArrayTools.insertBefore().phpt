<?php

/**
 * Test: Nette\ArrayTools::insertBefore() & insertAfter()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\ArrayTools;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$arr  = array(
	NULL => 'first',
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

dump( $arr );

output('First item');
$dolly = $arr;
ArrayTools::insertBefore($dolly, NULL, array('new' => 'value'));
dump( $dolly );

$dolly = $arr;
ArrayTools::insertAfter($dolly, NULL, array('new' => 'value'));
dump( $dolly );


output('Last item');
$dolly = $arr;
ArrayTools::insertBefore($dolly, 7, array('new' => 'value'));
dump( $dolly );

$dolly = $arr;
ArrayTools::insertAfter($dolly, 7, array('new' => 'value'));
dump( $dolly );


output('Undefined item');
$dolly = $arr;
ArrayTools::insertBefore($dolly, 'undefined', array('new' => 'value'));
dump( $dolly );

$dolly = $arr;
ArrayTools::insertAfter($dolly, 'undefined', array('new' => 'value'));
dump( $dolly );




__halt_compiler() ?>

------EXPECT------
array(4) {
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
}

First item

array(5) {
	"new" => string(5) "value"
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
}

array(5) {
	"" => string(5) "first"
	"new" => string(5) "value"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
}

Last item

array(5) {
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	"new" => string(5) "value"
	7 => string(6) "fourth"
}

array(5) {
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
	"new" => string(5) "value"
}

Undefined item

array(5) {
	"new" => string(5) "value"
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
}

array(5) {
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
	"new" => string(5) "value"
}
