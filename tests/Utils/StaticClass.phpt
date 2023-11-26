<?php

/**
 * Test: Nette\StaticClass
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\StaticClass;

	public static function method()
	{
	}
}

Assert::exception(
	fn() => new TestClass,
	Error::class,
	'Call to private TestClass::__construct() from global scope',
);
