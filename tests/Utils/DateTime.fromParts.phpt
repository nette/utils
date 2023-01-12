<?php

/**
 * Test: Nette\Utils\DateTime::fromParts().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::same('0001-12-09 00:00:00.000000', DateTime::fromParts(1, 12, 9)->format('Y-m-d H:i:s.u'));
Assert::same('0085-12-09 00:00:00.000000', DateTime::fromParts(85, 12, 9)->format('Y-m-d H:i:s.u'));
Assert::same('1985-01-01 00:00:00.000000', DateTime::fromParts(1985, 1, 1)->format('Y-m-d H:i:s.u'));
Assert::same('1985-12-19 00:00:00.000000', DateTime::fromParts(1985, 12, 19)->format('Y-m-d H:i:s.u'));
Assert::same('1985-12-09 01:02:00.000000', DateTime::fromParts(1985, 12, 9, 1, 2)->format('Y-m-d H:i:s.u'));
Assert::same('1985-12-09 01:02:03.000000', DateTime::fromParts(1985, 12, 9, 1, 2, 3)->format('Y-m-d H:i:s.u'));
Assert::same('1985-12-09 11:22:33.000000', DateTime::fromParts(1985, 12, 9, 11, 22, 33)->format('Y-m-d H:i:s.u'));
Assert::same('1985-12-09 11:22:59.123000', DateTime::fromParts(1985, 12, 9, 11, 22, 59.123)->format('Y-m-d H:i:s.u'));

Assert::exception(
	fn() => DateTime::fromParts(1985, 2, 29),
	Nette\InvalidArgumentException::class,
	"Invalid date '1985-02-29 00:00:0.00000'",
);

Assert::exception(
	fn() => DateTime::fromParts(0, 12, 9),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 0, 9),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 13, 9),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 0),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 32),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, -1),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 60),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, -1),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, 60),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, 0, -1),
	Nette\InvalidArgumentException::class,
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, 0, 60),
	Nette\InvalidArgumentException::class,
);
