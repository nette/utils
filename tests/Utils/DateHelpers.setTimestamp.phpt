<?php

/**
 * Test: Nette\Utils\DateHelpers::setTimestamp().
 */

use Tester\Assert;
use Nette\Utils\DateHelpers;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

$timestamp = 123456789;
$newTimestamp = $timestamp + 1000;

$mutable = DateHelpers::createMutable($timestamp);
$changedMutable = $mutable->setTimestamp($newTimestamp);
Assert::same($mutable, $changedMutable);
Assert::same($newTimestamp, $mutable->getTimestamp());
Assert::same($newTimestamp, $changedMutable->getTimestamp());

$immutable = DateHelpers::createImmutable($timestamp);
$changedImmutable = $immutable->setTimestamp($newTimestamp);
Assert::notSame($immutable, $changedImmutable);
Assert::same($timestamp, $immutable->getTimestamp());
Assert::same($newTimestamp, $changedImmutable->getTimestamp());
