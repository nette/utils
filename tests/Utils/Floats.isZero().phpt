<?php

/**
 * Test: Nette\Utils\Floats::isZero()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Floats::isZero(0));
Assert::true(Floats::isZero(0.0));
Assert::true(Floats::isZero(0x0));
Assert::false(Floats::isZero(-12.5));
Assert::false(Floats::isZero(0.2));
Assert::false(Floats::isZero(20));
Assert::false(Floats::isZero(-2));
Assert::false(Floats::isZero(0x5));
Assert::false(Floats::isZero(INF));
Assert::false(Floats::isZero(NAN));
