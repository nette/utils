<?php

/**
 * Test: Nette\Utils\DateHelpers::createImmutable().
 */

use Tester\Assert;
use Nette\Utils\DateHelpers;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

const FORMAT = 'Y-m-d H:i:s';

Assert::same('1978-01-23 11:40:00', DateHelpers::createImmutable(254400000)->format(FORMAT));
Assert::same(254400000, DateHelpers::createImmutable(254400000)->getTimestamp());

Assert::same('2050-08-13 11:40:00', DateHelpers::createImmutable(2544000000)->format(FORMAT));
Assert::same(is_int(2544000000) ? 2544000000 : '2544000000', DateHelpers::createImmutable(2544000000)->getTimestamp()); // 64 bit

Assert::same('1978-05-05 00:00:00', DateHelpers::createImmutable('1978-05-05')->format(FORMAT));

Assert::type(DateTimeImmutable::class, DateHelpers::createImmutable(new DateTime('1978-05-05')));

Assert::same('1978-05-05 00:00:00', DateHelpers::createImmutable(new DateTime('1978-05-05'))->format(FORMAT));
