<?php

/**
 * Test: Nette\Object properties.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
	public $foo;
}


(function () {
	$obj = new TestClass;
	unset($obj->foo);
	Assert::false(isset($obj->foo));

	// re-set
	$obj->foo = 'hello';
	Assert::same('hello', $obj->foo);
})();


(function () {
	// double unset
	$obj = new TestClass;
	unset($obj->foo);
	unset($obj->foo);
})();


(function () {
	// reading of unset property
	Assert::exception(function () {
		$obj = new TestClass;
		unset($obj->foo);
		$val = $obj->foo;
	}, Nette\MemberAccessException::class, 'Cannot read an undeclared property TestClass::$foo.');
})();
