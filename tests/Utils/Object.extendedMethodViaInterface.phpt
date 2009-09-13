<?php

/**
 * Test: Object ExtendedMethodViaInterface
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



function IFirst_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



function ISecond_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



TestClass::extensionMethod('IFirst::join', 'IFirst_join');
TestClass::extensionMethod('ISecond::join', 'ISecond_join');

$obj = new TestClass('Hello', 'World');
dump( $obj->join('*') );



__halt_compiler();

------EXPECT------
string(29) "ISecond_join says Hello*World"
