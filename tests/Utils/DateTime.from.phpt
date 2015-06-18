<?php

/**
 * Test: Nette\Utils\DateTime::from().
 */

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::same('1978-01-23 11:40:00', (string) DateTime::from(254400000));
Assert::same(254400000, DateTime::from(254400000)->getTimestamp());

Assert::same('2050-08-13 11:40:00', (string) DateTime::from(2544000000));
Assert::same(is_int(2544000000) ? 2544000000 : '2544000000', DateTime::from(2544000000)->getTimestamp()); // 64 bit

Assert::same('1978-05-05 00:00:00', (string) DateTime::from('1978-05-05'));

Assert::type('DateTime', DateTime::from(new DateTime('1978-05-05')));

Assert::same('1978-05-05 00:00:00', (string) DateTime::from(new DateTime('1978-05-05')));
