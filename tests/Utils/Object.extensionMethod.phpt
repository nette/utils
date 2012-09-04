<?php

/**
 * Test: Nette\Object extension method.
 *
 * @author     David Grudl
 * @package    Nette
 */




require __DIR__ . '/../bootstrap.php';



class TestClass extends Nette\Object
{
	public $foo = 'Hello', $bar = 'World';
}


function TestClass_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}


TestClass::extensionMethod('TestClass::join', 'TestClass_join');

$obj = new TestClass;
Assert::same( 'Hello*World', $obj->join('*') );
