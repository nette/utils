<?php

/**
 * Test: Nette\ArrayTools::getRef()
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
	1 => 'second',
	7 => array(
		'item' => 'third',
	),
);

output('Single item');

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, NULL);
$item = 'changed';
dump( $dolly );

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, 'undefined');
$item = 'changed';
dump( $dolly );


output('Traversing');

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, array());
$item = 'changed';
dump( $dolly );

$dolly = $arr;
$item = & ArrayTools::getRef($dolly, array(7, 'item'));
$item = 'changed';
dump( $dolly );


output('Error');

try {
	$dolly = $arr;
	$item = & ArrayTools::getRef($dolly, array(7, 'item', 3));
	$item = 'changed';
	dump( $dolly );

} catch (Exception $e) {
	dump( $e );
}




__halt_compiler();

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
