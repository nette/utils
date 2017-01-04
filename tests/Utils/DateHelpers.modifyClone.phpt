<?php

/**
 * Test: Nette\Utils\DateHelpers::modifyClone().
 */

use Tester\Assert;
use Nette\Utils\DateHelpers;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

$timestamp = 123456789;
$offset = 1000;

// test mutable
$mutable = DateHelpers::createMutable($timestamp);
$newMutable = DateHelpers::modifyClone($mutable, "+$offset seconds");
Assert::type(DateTime::class, $newMutable);
Assert::notSame($mutable, $newMutable);
Assert::same($timestamp, $mutable->getTimestamp());
Assert::same($timestamp + $offset, $newMutable->getTimestamp());

// test immutable
$immutable = DateHelpers::createImmutable($timestamp);
$newImmutable = DateHelpers::modifyClone($immutable, "+$offset seconds");
Assert::type(DateTimeImmutable::class, $newImmutable);
Assert::notSame($immutable, $newImmutable);
Assert::same($timestamp, $immutable->getTimestamp());
Assert::same($timestamp + $offset, $newImmutable->getTimestamp());
