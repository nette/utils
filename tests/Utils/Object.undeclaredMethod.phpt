<?php

/**
 * Test: Nette\Object undeclared method.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



Assert::throws(function() {
	$obj = new TestClass;
	$obj->undeclared();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::undeclared().');
