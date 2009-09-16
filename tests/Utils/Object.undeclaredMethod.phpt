<?php

/**
 * Test: Nette\Object undeclared method.
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
$obj->undeclared();



__halt_compiler();

------EXPECT------
Exception MemberAccessException: Call to undefined method TestClass::undeclared().
