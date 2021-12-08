<?php

/**
 * Test: Nette\Utils\Floats::isLowerThan()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Floats::isLessThan(-9, 9));
Assert::true(Floats::isLessThan(-9.7, 0.0));
Assert::true(Floats::isLessThan(10, 150));
Assert::false(Floats::isLessThan(0, 0.0));
Assert::false(Floats::isLessThan(10, 10));
Assert::false(Floats::isLessThan(-50, -50));
Assert::false(Floats::isLessThan(170, 150));
Assert::false(Floats::isLessThan(170.879, -20));
Assert::false(Floats::isLessThan(11.879, 0.0));

Assert::true(Floats::isLessThan(-INF, INF));
Assert::false(Floats::isLessThan(INF, INF));
Assert::false(Floats::isLessThan(INF, -INF));

Assert::exception(function () {
	Floats::isLessThan(NAN, NAN);
}, LogicException::class);
