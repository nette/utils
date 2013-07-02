<?php

/**
 * Test: Nette\Utils\Strings::trim()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( 'x',  Strings::trim(" \t\n\r\x00\x0B\xC2\xA0x") );
Assert::same( 'a b',  Strings::trim(' a b ') );
Assert::same( ' a b ',  Strings::trim(' a b ', '') );
Assert::same( 'e',  Strings::trim("\xc5\x98e-", "\xc5\x98-") ); // Ře-

Assert::exception(function() {
	Strings::trim("\xC2x\xA0");
}, 'Nette\Utils\RegexpException', NULL);
