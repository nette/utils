<?php

/**
 * Test: Object ExtendedMethodOldWay
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.2
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



function TestClass_prototype_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}

$obj = new TestClass('Hello', 'World');
dump( $obj->join('*') );



__halt_compiler();

------EXPECT------
string(11) "Hello*World"
