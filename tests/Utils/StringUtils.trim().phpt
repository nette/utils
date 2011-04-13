<?php

/**
 * Test: Nette\StringUtils::trim()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( 'x',  StringUtils::trim(" \t\n\r\x00\x0B\xC2\xA0x") );
Assert::same( 'a b',  StringUtils::trim(' a b ') );
Assert::same( ' a b ',  StringUtils::trim(' a b ', '') );
Assert::same( 'e',  StringUtils::trim("\xc5\x98e-", "\xc5\x98-") ); // Ře-

try {
	StringUtils::trim("\xC2x\xA0");
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', NULL, $e );
}
