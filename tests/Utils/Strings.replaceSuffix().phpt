<?php

/**
 * Test: Nette\Utils\Strings::replaceSuffix()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

Assert::same('abcabc', Strings::replaceSuffix('abcabc', 'xyz'));
Assert::same('abcxyz', Strings::replaceSuffix('abcabc', 'abc', 'xyz'));
