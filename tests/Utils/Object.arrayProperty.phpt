<?php

/**
 * Test: Nette\Object array property.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



$obj = new TestClass;
$obj->items[] = 'test';
dump( $obj->items[0] );

$obj->items = array();
$obj->items[] = 'one';
$obj->items[] = 'two';
dump( $obj->items[0] );
dump( $obj->items[1] );



__halt_compiler() ?>

------EXPECT------
string(4) "test"

string(3) "one"

string(3) "two"
