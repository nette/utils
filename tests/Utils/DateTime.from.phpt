<?php

/**
 * Test: Nette\Utils\DateTime::from().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::same('1978-01-23 11:40:00', (string) DateTime::from(254400000));
Assert::same('1978-01-23 11:40:00', (string) (new DateTime)->setTimestamp(254400000));
Assert::same(254400000, DateTime::from(254400000)->getTimestamp());

Assert::same(time() + 60, (int) DateTime::from(60)->format('U'));
Assert::same(PHP_VERSION_ID < 80100 ? '2050-08-13 11:40:00' : '2050-08-13 12:40:00', (string) DateTime::from(2544000000));
Assert::same(PHP_VERSION_ID < 80100 ? '2050-08-13 11:40:00' : '2050-08-13 12:40:00', (string) (new DateTime)->setTimestamp(2544000000));
Assert::same(is_int(2544000000) ? 2544000000 : '2544000000', DateTime::from(2544000000)->getTimestamp()); // 64 bit

Assert::same('1978-05-05 00:00:00', (string) DateTime::from('1978-05-05'));

Assert::same((new \Datetime)->format('Y-m-d H:i:s'), (string) DateTime::from(null));

Assert::type(DateTime::class, DateTime::from(new \DateTime('1978-05-05')));

Assert::same('1978-05-05 12:00:00.123450', DateTime::from(new DateTime('1978-05-05 12:00:00.12345'))->format('Y-m-d H:i:s.u'));
