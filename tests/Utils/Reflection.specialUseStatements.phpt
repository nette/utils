<?php

/**
 * Test: Ingnoring PHP 7 non-class use statements.
 * @phpVersion 7
 */

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


require __DIR__ . '/fixtures.reflection/expandClass.special.php';

Assert::same(
	['AAA' => 'AAA', 'B' => 'BBB'],
	Reflection::getUseStatements(new ReflectionClass('Test\Space\Bar'))
);
