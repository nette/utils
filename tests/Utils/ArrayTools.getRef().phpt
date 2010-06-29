<?php

/**
 * Test: Nette\ArrayTools::getRef()
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

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, NULL);
$item = 'changed';
T::dump( $dolly );

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, 'undefined');
$item = 'changed';
T::dump( $dolly );


T::note('Traversing');

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, array());
$item = 'changed';
T::dump( $dolly );

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, array(7, 'item'));
$item = 'changed';
T::dump( $dolly );


T::note('Error');

try {
	$dolly = $arr;
	$item = & ArrayTools::getRef($dolly, array(7, 'item', 3));
	$item = 'changed';
	T::dump( $dolly );

} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler() ?>

------EXPECT------
Single item

array(3) {
	"" => string(7) "changed"
	1 => string(6) "second"
	7 => array(1) {
		"item" => string(5) "third"
	}
}

array(4) {
	"" => string(5) "first"
	1 => string(6) "second"
	7 => array(1) {
		"item" => string(5) "third"
	}
	"undefined" => string(7) "changed"
}

Traversing

string(7) "changed"

array(3) {
	"" => string(5) "first"
	1 => string(6) "second"
	7 => array(1) {
		"item" => string(7) "changed"
	}
}

Error

Exception InvalidArgumentException: Traversed item is not an array.
