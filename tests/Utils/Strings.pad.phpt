<?php

/**
 * Test: Nette\Utils\Strings::padLeft() & padRight()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('ŤOUŤOUŤŽLU', Strings::padLeft("\u{17D}LU", 10, "\u{164}OU"));
Assert::same('ŤOUŤOUŽLU', Strings::padLeft("\u{17D}LU", 9, "\u{164}OU"));
Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", 3, "\u{164}OU"));
Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", 0, "\u{164}OU"));
Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", -1, "\u{164}OU"));
Assert::same('ŤŤŤŤŤŤŤŽLU', Strings::padLeft("\u{17D}LU", 10, "\u{164}"));
Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", 3, "\u{164}"));
Assert::same('       ŽLU', Strings::padLeft("\u{17D}LU", 10));


Assert::same('ŽLUŤOUŤOUŤ', Strings::padRight("\u{17D}LU", 10, "\u{164}OU"));
Assert::same('ŽLUŤOUŤOU', Strings::padRight("\u{17D}LU", 9, "\u{164}OU"));
Assert::same('ŽLU', Strings::padRight("\u{17D}LU", 3, "\u{164}OU"));
Assert::same('ŽLU', Strings::padRight("\u{17D}LU", 0, "\u{164}OU"));
Assert::same('ŽLU', Strings::padRight("\u{17D}LU", -1, "\u{164}OU"));
Assert::same('ŽLUŤŤŤŤŤŤŤ', Strings::padRight("\u{17D}LU", 10, "\u{164}"));
Assert::same('ŽLU', Strings::padRight("\u{17D}LU", 3, "\u{164}"));
Assert::same('ŽLU       ', Strings::padRight("\u{17D}LU", 10));
