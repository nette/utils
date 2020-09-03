<?php

/**
 * Test: Nette\Utils\Floats::isInteger()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Floats::isInteger(0));
Assert::true(Floats::isInteger(0.0));
Assert::true(Floats::isInteger(5.0));
Assert::true(Floats::isInteger(-5.0));
Assert::true(Floats::isInteger(-5));
Assert::true(Floats::isInteger((1 - (0.1 + 0.2)) * 10));
Assert::false(Floats::isInteger(-5.1));
Assert::false(Floats::isInteger(0.000001));
Assert::false(Floats::isInteger(NAN));
Assert::false(Floats::isInteger(INF));
