<?php

/**
 * Test: Object reference to property
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
$obj->foo = 'hello';
@$x = & $obj->foo;
$x = 'changed by reference';
dump( $obj->foo );



__halt_compiler();

------EXPECT------
string(5) "hello"
