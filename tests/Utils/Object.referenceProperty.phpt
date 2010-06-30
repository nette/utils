<?php

/**
 * Test: Nette\Object reference to property.
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
$obj->foo = 'hello';
@$x = & $obj->foo;
$x = 'changed by reference';
T::dump( $obj->foo );



__halt_compiler() ?>

------EXPECT------
"hello"
