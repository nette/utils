<?php

/**
 * Test: Object Class
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
dump( $obj->getClass() );
dump( $obj->class );



__halt_compiler();

------EXPECT------
string(9) "TestClass"

string(9) "TestClass"
