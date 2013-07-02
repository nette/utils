<?php

/**
 * Test: Nette\Object undeclared method.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
}


Assert::exception(function() {
	$obj = new TestClass;
	$obj->undeclared();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::undeclared().');
