<?php

/**
 * Test: Nette\Utils\Strings::platformNewLines()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$n = PHP_EOL;
Assert::same("{$n} {$n} {$n}{$n}", Strings::platformNewLines("\r\n \r \n\n"));
Assert::same("{$n}{$n}", Strings::platformNewLines("\n\r"));
Assert::same("{$n} {$n}", Strings::platformNewLines("\u{2028} \u{2029}"));
