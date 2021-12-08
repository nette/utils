<?php

/**
 * Test: Nette\Utils\Strings::webalize()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('zlutoucky-kun-oeooo', Strings::webalize("&\u{17D}LU\u{164}OU\u{10C}K\u{DD} K\u{16E}\u{147} \u{F6}\u{151}\u{F4}o!")); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same('ZLUTOUCKY-KUN-oeooo', Strings::webalize("&\u{17D}LU\u{164}OU\u{10C}K\u{DD} K\u{16E}\u{147} \u{F6}\u{151}\u{F4}o!", null, false)); // &ŽLUŤOUČKÝ KŮŇ öőôo!
if (class_exists('Transliterator') && Transliterator::create('Any-Latin; Latin-ASCII')) {
	Assert::same('1-4-!', Strings::webalize("\u{BC} !", '!'));
}
Assert::same('a-b', Strings::webalize("a\u{A0}b")); // non-breaking space
Assert::exception(function () {
	Strings::toAscii("0123456789\xFF");
}, Nette\Utils\RegexpException::class, null, PREG_BAD_UTF8_ERROR);
