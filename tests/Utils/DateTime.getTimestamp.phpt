<?php

/**
 * Test: Nette\Utils\DateTime::getTimestamp().
 */

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';

error_reporting(error_reporting() & ~E_USER_DEPRECATED);


date_default_timezone_set('Europe/Prague');

$timestamp = 123456789;
$dt = DateTime::from($timestamp);
Assert::same($timestamp, $dt->getTimestamp());
