<?php

/**
 * Test: Nette\Utils\Floats::compare()
 */

declare(strict_types=1);

use Nette\Utils\Floats;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(0, Floats::compare(0, 0));
Assert::same(0, Floats::compare(0.0, 0));
Assert::same(0, Floats::compare(0, 0x0));
Assert::same(1, Floats::compare(0, -25.7));
Assert::same(-1, Floats::compare(-2, 30.7));
Assert::same(1, Floats::compare(0.0, -5));
Assert::same(1, Floats::compare(20, 10));
Assert::same(-1, Floats::compare(20, 30));
Assert::same(1, Floats::compare(-20, -30));
Assert::same(-1, Floats::compare(-50, -30));

Assert::exception(function () {
	Floats::compare(NAN, -30);
}, LogicException::class);

Assert::exception(function () {
	Floats::compare(6, NAN);
}, LogicException::class);

Assert::exception(function () {
	Floats::compare(NAN, NAN);
}, LogicException::class);
