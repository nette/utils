<?php

/**
 * Test: Nette\Utils\DateTime::setTime().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');

Assert::same('1.8.2050', (new DateTime('2050-08-13 12:40:50.86'))->setDate(null, null, 1)->format('j.n.Y'));
Assert::same('13.1.2050', (new DateTime('2050-08-13 12:40:50.86'))->setDate(null, 1, null)->format('j.n.Y'));
Assert::same('13.8.2020', (new DateTime('2050-08-13 12:40:50.86'))->setDate(2020, null, null)->format('j.n.Y'));
Assert::same('13.8.2050', (new DateTime('2050-08-13 12:40:50.86'))->setDate(null, null, null)->format('j.n.Y'));
