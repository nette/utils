<?php

/**
 * Test: Nette\Utils\Floats::isGreaterOrEqualThan()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::false(Floats::isGreaterThanOrEqualTo(-9, 9));
Assert::false(Floats::isGreaterThanOrEqualTo(-9.7, 0.0));
Assert::false(Floats::isGreaterThanOrEqualTo(10, 150));
Assert::true(Floats::isGreaterThanOrEqualTo(0, 0.0));
Assert::true(Floats::isGreaterThanOrEqualTo(10, 10));
Assert::true(Floats::isGreaterThanOrEqualTo(-50, -50));
Assert::true(Floats::isGreaterThanOrEqualTo(170, 150));
Assert::true(Floats::isGreaterThanOrEqualTo(170.879, -20));
Assert::true(Floats::isGreaterThanOrEqualTo(11.879, 0.0));

Assert::true(Floats::isGreaterThanOrEqualTo(INF, INF));
Assert::true(Floats::isGreaterThanOrEqualTo(INF, -INF));
Assert::false(Floats::isGreaterThanOrEqualTo(-INF, INF));

Assert::exception(
	fn() => Floats::isGreaterThanOrEqualTo(NAN, NAN),
	LogicException::class,
);
