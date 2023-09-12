<?php

/**
 * Test: Nette\Utils\DateTime::setTime().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');

Assert::same('00:00:00.000000', (new DateTime('2050-08-13 12:40:50.86'))->setTime(0, 0)->format('H:i:s.u'));
Assert::same('12:00:00.000000', (new DateTime('2050-08-13 12:40:50.86'))->setTime(null, 0)->format('H:i:s.u'));
Assert::same('00:40:00.000000', (new DateTime('2050-08-13 12:40:50.86'))->setTime(0, null)->format('H:i:s.u'));
Assert::same('00:00:50.000000', (new DateTime('2050-08-13 12:40:50.86'))->setTime(0, 0, null)->format('H:i:s.u'));
Assert::same('00:00:00.860000', (new DateTime('2050-08-13 12:40:50.86'))->setTime(0, 0, 0, null)->format('H:i:s.u'));
Assert::same('12:40:50.860000', (new DateTime('2050-08-13 12:40:50.86'))->setTime(null, null, null, null)->format('H:i:s.u'));
