<?php

/**
 * Test: Ingnoring PHP 7 non-class use statements.
 * @phpVersion 7
 */

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


require __DIR__ . '/files/expandClass.nonClassUse.php';

Assert::same(
	[],
	Reflection::getUseStatements(new ReflectionClass('NonClassUseTest'))
);
