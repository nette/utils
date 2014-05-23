<?php

/**
 * Test: Nette\Utils\Strings::normalizeNewLines()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "\n \n \n\n",  Strings::normalizeNewLines("\r\n \r \n\n") );
Assert::same( "\n\n",  Strings::normalizeNewLines("\n\r") );
