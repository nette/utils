<?php

/**
 * Test: Nette\Object extension method.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\LegacyObject
{
	public $foo = 'Hello';

	public $bar = 'World';
}


@TestClass::extensionMethod('join', $func = function (TestClass $that, $separator) { // is deprecated
	return $that->foo . $separator . $that->bar;
});

$obj = new TestClass;
Assert::same('Hello*World', $obj->join('*'));


Assert::same(
	['join' => $func],
	@Nette\Utils\ObjectMixin::getExtensionMethods(TestClass::class) // is deprecated
);

Assert::same(
	[],
	@Nette\Utils\ObjectMixin::getExtensionMethods(Nette\LegacyObject::class) // is deprecated
);

Assert::exception(function () {
	$obj = new TestClass;
	$obj->joi();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::joi(), did you mean join()?');
