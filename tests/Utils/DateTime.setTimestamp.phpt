<?php

/**
 * Test: Nette\Utils\DateTime::setTimestamp().
 */

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

$timestamp = 123456789;
$newTimestamp = $timestamp + 1000;
$dt = DateTime::from($timestamp);
$dt->setTimestamp($newTimestamp);
Assert::same($newTimestamp, $dt->getTimestamp());
Assert::same((string) $newTimestamp, $dt->format('U'));
