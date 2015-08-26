<?php

/**
 * Test: Nette\Utils\DateTime::createFromFormat().
 */

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';

error_reporting(error_reporting() & ~E_USER_DEPRECATED);


date_default_timezone_set('Europe/Prague');

Assert::type(Nette\Utils\DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00'));
Assert::type(Nette\Utils\DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00', new DateTimeZone('Europe/Prague')));

Assert::same('2050-08-13 11:40:00', (string) DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00'));

Assert::same('Europe/Prague', DateTime::createFromFormat('Y', '2050')->getTimezone()->getName());
Assert::same('Europe/Bratislava', DateTime::createFromFormat('Y', '2050', 'Europe/Bratislava')->getTimezone()->getName());

Assert::error(function () {
	DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00', 5);
}, Nette\InvalidArgumentException::class, 'Invalid timezone given');

Assert::false(DateTime::createFromFormat('Y-m-d', '2014-10'));
