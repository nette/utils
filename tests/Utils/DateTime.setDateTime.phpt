<?php

/**
 * Test: Nette\Utils\DateTime: strict.
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => (new DateTime)->setDate(1978, 2, 31),
	Throwable::class,
	'The date 1978-02-31 is not valid.',
);

Assert::exception(
	fn() => (new DateTime)->setTime(0, 60),
	Throwable::class,
	'The time 00:60:00.00000 is not valid.',
);
