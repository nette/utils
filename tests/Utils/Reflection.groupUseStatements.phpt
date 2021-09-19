<?php

/**
 * Test: Parsing PHP 7 group use statements.
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


require __DIR__ . '/fixtures.reflection/expandClass.groupUse.php';

Assert::same(
	['A' => 'A\B\A', 'C' => 'A\B\B\C', 'D' => 'A\B\C', 'E' => 'D\E'],
	Reflection::getUseStatements(new ReflectionClass('GroupUseTest')),
);
