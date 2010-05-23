<?php

/**
 * Test: Nette\Object extension method.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



function TestClass_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}

TestClass::extensionMethod('TestClass::join', 'TestClass_join');

$obj = new TestClass('Hello', 'World');
dump( $obj->join('*') );



__halt_compiler() ?>

------EXPECT------
string(11) "Hello*World"
