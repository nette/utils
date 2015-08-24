<?php

/**
 * Test: Nette\Utils\DateTime & JSON.
 */

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';

error_reporting(error_reporting() & ~E_USER_DEPRECATED);


date_default_timezone_set('Europe/Prague');

Assert::same('"1978-01-23T11:40:00+01:00"', json_encode(DateTime::from(254400000)));
