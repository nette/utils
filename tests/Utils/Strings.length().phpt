<?php

/**
 * Test: Nette\Utils\Strings::length()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(0, Strings::length(''));
Assert::same(20, Strings::length("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n")); // Iñtërnâtiônàlizætiøn
Assert::same(1, Strings::length("\u{10000}")); // U+010000
Assert::same(6, Strings::length("ma\u{F1}ana"));   // mañana, U+00F1
Assert::same(7, Strings::length("man\u{303}ana"));  // mañana, U+006E + U+0303 (combining character)
