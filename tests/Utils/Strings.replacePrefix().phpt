<?php

/**
 * Test: Nette\Utils\Strings::replacePrefix()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

Assert::same('abc', Strings::replacePrefix('abcabc', 'abc'));
Assert::same('xyzabc', Strings::replacePrefix('abcabc', 'abc', 'xyz'));
