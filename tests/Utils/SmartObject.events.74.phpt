<?php

/**
 * Test: Nette\SmartObject event handlers.
 * @phpversion 7.4
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

	public array $onEvent;
}


Assert::noError(function () {
	$obj = new TestClass;
	$obj->onEvent();
});
