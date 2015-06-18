<?php

/**
 * Test: Nette\Utils\Strings::webalize()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('zlutoucky-kun-oooo', Strings::webalize("&\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o!")); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same('ZLUTOUCKY-KUN-oooo', Strings::webalize("&\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o!", NULL, FALSE)); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same('1-4-!',  Strings::webalize("\xc2\xBC !", '!'));
Assert::same('a-b', Strings::webalize("a\xC2\xA0b")); // non-breaking space
