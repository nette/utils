<?php

/**
 * Test: Nette\String::trim()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



Assert::same( 'x',  String::trim(" \t\n\r\x00\x0B\xC2\xA0x") );
Assert::same( 'a b',  String::trim(' a b ') );
Assert::same( ' a b ',  String::trim(' a b ', '') );
Assert::same( 'e',  String::trim("\xc5\x98e-", "\xc5\x98-") ); // Ře-

try {
	String::trim("\xC2x\xA0");
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', NULL, $e );
}
