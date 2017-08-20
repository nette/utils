<?php

/**
 * Test: Nette\Utils\Strings::bytesCount()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(0, Strings::bytesCount(''));
Assert::same(20, Strings::bytesCount("aaaaaaaaaaaaaaaaaaaa"));
Assert::same(27, Strings::bytesCount("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n"));
Assert::same(2, Strings::bytesCount("š"));
Assert::same(7, Strings::bytesCount("ma\u{F1}ana"));
Assert::same(8, Strings::bytesCount("man\u{303}ana"));
