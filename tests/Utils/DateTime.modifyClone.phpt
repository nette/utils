<?php

/**
 * Test: Nette\Utils\DateTime::modifyClone().
 */

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

$timestamp = 123456789;
$offset = 1000;

$dt = DateTime::from($timestamp);
$newDt = $dt->modifyClone("+$offset seconds");
Assert::notSame($dt, $newDt);
Assert::same($timestamp, $dt->getTimestamp());
Assert::same($timestamp + $offset, $newDt->getTimestamp());
