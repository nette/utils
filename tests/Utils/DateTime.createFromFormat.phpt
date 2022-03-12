<?php

/**
 * Test: Nette\Utils\DateTime::createFromFormat().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::type(DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00'));
Assert::type(DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00', new DateTimeZone('Europe/Prague')));

Assert::same('2050-08-13 11:40:00.123450', DateTime::createFromFormat('Y-m-d H:i:s.u', '2050-08-13 11:40:00.12345')->format('Y-m-d H:i:s.u'));

Assert::same('Europe/Prague', DateTime::createFromFormat('Y', '2050')->getTimezone()->getName());
Assert::same('Europe/Bratislava', DateTime::createFromFormat('Y', '2050', 'Europe/Bratislava')->getTimezone()->getName());

Assert::exception(
	fn() => DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00', 5),
	Nette\InvalidArgumentException::class,
	'Invalid timezone given',
);

Assert::false(DateTime::createFromFormat('Y-m-d', '2014-10'));
