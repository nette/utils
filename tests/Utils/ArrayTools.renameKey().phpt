<?php

/**
 * Test: Nette\ArrayTools::renameKey()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../NetteTest/initialize.php';



$arr  = array(
	NULL => 'first',
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

dump( $arr );

ArrayTools::renameKey($arr, '1', 'new1');
ArrayTools::renameKey($arr, 0, 'new2');
ArrayTools::renameKey($arr, NULL, 'new3');
ArrayTools::renameKey($arr, '', 'new4');
ArrayTools::renameKey($arr, 'undefined', 'new5');

dump( $arr );



__halt_compiler() ?>

------EXPECT------
array(4) {
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
}

array(4) {
	"new3" => string(5) "first"
	"new2" => string(6) "second"
	"new1" => string(5) "third"
	7 => string(6) "fourth"
}
