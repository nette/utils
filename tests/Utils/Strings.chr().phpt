<?php

/**
 * Test: Nette\Utils\Strings::chr()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "\x00",  Strings::chr(0) ); // #0
Assert::same( "\xc3\xbf",  Strings::chr(255) ); // #255 in UTF-8
