<?php

/**
 * Test: Nette\Utils\Floats::isLowerOrEqualThan()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Floats::isLessThanOrEqualTo(-9, 9));
Assert::true(Floats::isLessThanOrEqualTo(-9.7, 0.0));
Assert::true(Floats::isLessThanOrEqualTo(10, 150));
Assert::true(Floats::isLessThanOrEqualTo(0, 0.0));
Assert::true(Floats::isLessThanOrEqualTo(10, 10));
Assert::true(Floats::isLessThanOrEqualTo(-50, -50));
Assert::false(Floats::isLessThanOrEqualTo(170, 150));
Assert::false(Floats::isLessThanOrEqualTo(170.879, -20));
Assert::false(Floats::isLessThanOrEqualTo(11.879, 0.0));

Assert::true(Floats::isLessThanOrEqualTo(-INF, INF));
Assert::true(Floats::isLessThanOrEqualTo(INF, INF));
Assert::false(Floats::isLessThanOrEqualTo(INF, -INF));

Assert::exception(function () {
	Floats::isLessThanOrEqualTo(NAN, NAN);
}, LogicException::class);
