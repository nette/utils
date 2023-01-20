<?php

/**
 * Test: Nette\Utils\Strings::unixNewLines()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same("\n \n \n\n", Strings::unixNewLines("\r\n \r \n\n"));
Assert::same("\n\n", Strings::unixNewLines("\n\r"));
Assert::same("\n \n", Strings::unixNewLines("\u{2028} \u{2029}"));
