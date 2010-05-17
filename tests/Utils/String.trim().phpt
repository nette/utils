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



require __DIR__ . '/../NetteTest/initialize.php';



Assert::same( "x",  String::trim(" \t\n\r\x00\x0B\xC2\xA0x") );
Assert::same( "a b",  String::trim(" a b ") );
Assert::same( " a b ",  String::trim(" a b ", '') );
Assert::same( "e",  String::trim("\xc5\x98e-", "\xc5\x98-") ); // Ře-

try {
	String::trim("\xC2x\xA0");
} catch (Exception $e) {
	dump($e);
}



__halt_compiler();

------EXPECT------
Exception Nette\RegexpException: #4 Malformed UTF-8 data (pattern: %A%)
