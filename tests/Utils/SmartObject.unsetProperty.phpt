<?php

/**
 * Test: Nette\SmartObject properties.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

	public $foo;
}


test('allows unsetting and reassigning a declared property', function () {
	$obj = new TestClass;
	unset($obj->foo);
	Assert::false(isset($obj->foo));

	// re-set
	$obj->foo = 'hello';
	Assert::same('hello', $obj->foo);
});


test('multiple unsets on a property are permitted', function () {
	$obj = new TestClass;
	unset($obj->foo, $obj->foo);
});


test('accessing an unset property triggers an exception', function () {
	Assert::exception(function () {
		$obj = new TestClass;
		unset($obj->foo);
		$val = $obj->foo;
	}, Nette\MemberAccessException::class, 'Cannot read an undeclared property TestClass::$foo.');
});
