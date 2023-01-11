<?php

/**
 * Test: Ingnoring PHP 7 non-class use statements.
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


require __DIR__ . '/fixtures.reflection/expandClass.nonClassUse.php';

Assert::same(
	[],
	Reflection::getUseStatements(new ReflectionClass('NonClassUseTest')),
);
