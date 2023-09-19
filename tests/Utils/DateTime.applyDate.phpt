<?php

/**
 * Test: Nette\Utils\DateTime::setTime().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');

Assert::same('13.8.2050', (new DateTime('2050-08-13 12:40:50.86'))->applyDate()->format('j.n.Y'));
Assert::same('2.1.2000', (new DateTime('2050-08-13 12:40:50.86'))->applyDate(2000, 1, 2)->format('j.n.Y'));
Assert::same('13.8.2000', (new DateTime('2050-08-13 12:40:50.86'))->applyDate(2000)->format('j.n.Y'));
Assert::same('13.1.2050', (new DateTime('2050-08-13 12:40:50.86'))->applyDate(null, 1)->format('j.n.Y'));
Assert::same('2.8.2050', (new DateTime('2050-08-13 12:40:50.86'))->applyDate(null, null, 2)->format('j.n.Y'));
