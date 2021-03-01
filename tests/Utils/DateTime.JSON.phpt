<?php

/**
 * Test: Nette\Utils\DateTime & JSON.
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::same('"1978-01-23T11:40:00+01:00"', json_encode(DateTime::from(254_400_000)));
