<?php

/**
 * Test: Nette\Object reflection.
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
T::dump( $obj->getReflection()->getName() );
T::dump( $obj->Reflection->getName() );



__halt_compiler() ?>

------EXPECT------
string(9) "TestClass"

string(9) "TestClass"
