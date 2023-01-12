<?php

/**
 * Test: Nette\Utils\Floats::areEqual()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Floats::areEqual(9, 9));
Assert::true(Floats::areEqual(9, 9.0));
Assert::true(Floats::areEqual(3.0, 3));
Assert::true(Floats::areEqual(0.0, 0));
Assert::true(Floats::areEqual(0.0, 0.0));
Assert::true(Floats::areEqual(0.1 + 0.2, 0.3));
Assert::true(Floats::areEqual(0.1 - 0.5, -0.4));
Assert::false(Floats::areEqual(0.0, 5));
Assert::false(Floats::areEqual(-5, 5));
Assert::false(Floats::areEqual(0.001, 0.01));

$float1 = 1 / 3;
$float2 = 1 - 2 / 3;
Assert::true(Floats::areEqual($float1, $float2));
Assert::true(Floats::areEqual($float1 * 1e9, $float2 * 1e9));
Assert::true(Floats::areEqual($float1 - $float2, 0.0));
Assert::true(Floats::areEqual($float1 - $float2 + 123, $float2 - $float1 + 123));
Assert::true(Floats::areEqual($float1 - $float2, $float2 - $float1));

Assert::true(Floats::areEqual(INF, INF));
Assert::false(Floats::areEqual(INF, -INF));
Assert::false(Floats::areEqual(-INF, INF));

Assert::exception(
	fn() => Floats::areEqual(NAN, NAN),
	LogicException::class,
);
