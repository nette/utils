<?php

/**
 * Test: Nette\Utils\Strings::webalize()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('zlutoucky-kun-oooo', Strings::webalize("&\u{17D}LU\u{164}OU\u{10C}K\u{DD} K\u{16E}\u{147} \u{F6}\u{151}\u{F4}o!")); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same('ZLUTOUCKY-KUN-oooo', Strings::webalize("&\u{17D}LU\u{164}OU\u{10C}K\u{DD} K\u{16E}\u{147} \u{F6}\u{151}\u{F4}o!", null, false)); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same('1-4-!', Strings::webalize("\u{BC} !", '!'));
Assert::same('a-b', Strings::webalize("a\u{A0}b")); // non-breaking space
