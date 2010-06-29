<?php

/**
 * Test: Nette\Object array property.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Object.inc';



$obj = new TestClass;
$obj->items[] = 'test';
T::dump( $obj->items[0] );

$obj->items = array();
$obj->items[] = 'one';
$obj->items[] = 'two';
T::dump( $obj->items[0] );
T::dump( $obj->items[1] );



__halt_compiler() ?>

------EXPECT------
string(4) "test"

string(3) "one"

string(3) "two"
