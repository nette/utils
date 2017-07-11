<?php

/**
 * Test: Nette\Utils\Strings::normalizeNewLines()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same("\n \n \n\n", Strings::normalizeNewLines("\r\n \r \n\n"));
Assert::same("\n\n", Strings::normalizeNewLines("\n\r"));
