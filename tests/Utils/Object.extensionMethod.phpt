<?php

/**
 * Test: Nette\Object extension method.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



function TestClass_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}

TestClass::extensionMethod('TestClass::join', 'TestClass_join');

$obj = new TestClass('Hello', 'World');
Assert::same( 'Hello*World', $obj->join('*') );
