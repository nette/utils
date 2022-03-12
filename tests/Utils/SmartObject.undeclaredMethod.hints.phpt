<?php

/**
 * Test: Nette\SmartObject undeclared method hints.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

	private function methodO()
	{
	}


	public function methodO2()
	{
	}


	private static function methodS()
	{
	}


	public static function methodS2()
	{
	}
}


$obj = new TestClass;
Assert::exception(
	fn() => $obj->abc(),
	Nette\MemberAccessException::class,
	'Call to undefined method TestClass::abc().',
);

Assert::exception(
	fn() => $obj->method(),
	Nette\MemberAccessException::class,
	'Call to undefined method TestClass::method(), did you mean methodO2()?',
);

Assert::exception(
	fn() => TestClass::abc(),
	Nette\MemberAccessException::class,
	'Call to undefined static method TestClass::abc().',
);

Assert::exception(
	fn() => TestClass::method(),
	Nette\MemberAccessException::class,
	'Call to undefined static method TestClass::method(), did you mean methodS2()?',
);

if (extension_loaded('gd')) {
	Assert::exception(
		fn() => Nette\Utils\Image::fromBlank(1, 1)->filledElippse(),
		Nette\MemberAccessException::class,
		'Call to undefined method Nette\Utils\Image::filledElippse(), did you mean filledEllipse()?',
	);
}
