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



try {
	$obj = new TestClass;
	$obj->undeclared();

	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\MemberAccessException', 'Call to undefined method TestClass::undeclared().', $e );
}
