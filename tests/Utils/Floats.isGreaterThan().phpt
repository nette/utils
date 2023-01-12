<?php

/**
 * Test: Nette\Utils\Floats::isGreaterThan()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::false(Floats::isGreaterThan(-9, 9));
Assert::false(Floats::isGreaterThan(-9.7, 0.0));
Assert::false(Floats::isGreaterThan(10, 150));
Assert::false(Floats::isGreaterThan(0, 0.0));
Assert::false(Floats::isGreaterThan(10, 10));
Assert::false(Floats::isGreaterThan(-50, -50));
Assert::true(Floats::isGreaterThan(170, 150));
Assert::true(Floats::isGreaterThan(170.879, -20));
Assert::true(Floats::isGreaterThan(11.879, 0.0));

Assert::true(Floats::isGreaterThan(INF, -INF));
Assert::false(Floats::isGreaterThan(INF, INF));
Assert::false(Floats::isGreaterThan(-INF, INF));

Assert::exception(
	fn() => Floats::isGreaterThan(NAN, NAN),
	LogicException::class,
);
