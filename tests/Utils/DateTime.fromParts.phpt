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
	Throwable::class,
	'The date 1985-02-29 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(0, 12, 9),
	Throwable::class,
	'The date 0000-12-09 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 0, 9),
	Throwable::class,
	'The date 1985-00-09 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 13, 9),
	Throwable::class,
	'The date 1985-13-09 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 0),
	Throwable::class,
	'The date 1985-12-00 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 32),
	Throwable::class,
	'The date 1985-12-32 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, -1),
	Throwable::class,
	'The time -1:00:00.00000 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 60),
	Throwable::class,
	'The time 60:00:00.00000 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, -1),
	Throwable::class,
	'The time 00:-1:00.00000 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, 60),
	Throwable::class,
	'The time 00:60:00.00000 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, 0, -1),
	Throwable::class,
	'The time 00:00:-1.00000 is not valid.',
);

Assert::exception(
	fn() => DateTime::fromParts(1985, 12, 9, 0, 0, 60),
	Throwable::class,
	'The time 00:00:60.00000 is not valid.',
);
