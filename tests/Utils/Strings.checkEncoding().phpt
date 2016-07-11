<?php

/**
 * Test: Nette\Utils\Strings::checkEncoding()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Strings::checkEncoding("\u{17E}lu\u{165}ou\u{10D}k\u{FD}")); // UTF-8   žluťoučký
Assert::true(Strings::checkEncoding("\x01")); // C0
Assert::false(Strings::checkEncoding("\xed\xa0\x80")); // surrogate pairs   xD800
Assert::false(Strings::checkEncoding("\xf4\x90\x80\x80")); // out of range   x110000
