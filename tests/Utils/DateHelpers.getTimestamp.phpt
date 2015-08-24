<?php

/**
 * Test: Nette\DateHelpers::getTimestamp().
 */

use Tester\Assert;
use Nette\Utils\DateHelpers;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

$timestamp = 123456789;

$mutable = DateHelpers::createMutable($timestamp);
Assert::same($timestamp, $mutable->getTimestamp());

$immutable = DateHelpers::createImmutable($timestamp);
Assert::same($timestamp, $immutable->getTimestamp());
