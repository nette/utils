<?php

/**
 * Test: Nette\Utils\Strings::substring()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

$s = "man\u{303}ana"; // mañana, U+006E + U+0303 (combining character)

// zero, zero
Assert::same('', Strings::substring($s, 0, 0));

// zero, default
Assert::same("man\u{303}ana", Strings::substring($s, 0));

// zero, positive
Assert::same('man', Strings::substring($s, 0, 3));
Assert::same("man\u{303}", Strings::substring($s, 0, 4));

/// zero, negative
Assert::same("man\u{303}", Strings::substring($s, 0, -3));
Assert::same('man', Strings::substring($s, 0, -4));

// positive, zero
Assert::same('', Strings::substring($s, 3, 0));

// positive, default
Assert::same("\u{303}ana", Strings::substring($s, 3));
Assert::same('ana', Strings::substring($s, 4));

// positive, positive
Assert::same('an', Strings::substring($s, 1, 2));
Assert::same("an\u{303}", Strings::substring($s, 1, 3));
Assert::same("n\u{303}", Strings::substring($s, 2, 2));
Assert::same("\u{303}a", Strings::substring($s, 3, 2));

// positive, negative
Assert::same("an\u{303}", Strings::substring($s, 1, -3));
Assert::same('an', Strings::substring($s, 1, -4));

// negative, zero
Assert::same('', Strings::substring($s, -3, 0));

// negative, default
Assert::same("\u{303}ana", Strings::substring($s, -4));
Assert::same("n\u{303}ana", Strings::substring($s, -5));

// negative, positive
Assert::same("\u{303}a", Strings::substring($s, -4, 2));
Assert::same('n', Strings::substring($s, -5, 1));

// negative, negative
Assert::same("n\u{303}", Strings::substring($s, -5, -3));
Assert::same('n', Strings::substring($s, -5, -4));
Assert::same('', Strings::substring($s, -5, -5));
