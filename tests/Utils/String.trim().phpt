<?php

/**
 * Test: String::trim()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::trim(" \t\n\r\x00\x0B\xC2\xA0x") ); // "x"
dump( String::trim("\xC2x\xA0") ); // ""
dump( String::trim(" a b ") ); // "a b"
dump( String::trim(" a b ", '') ); // " a b "
dump( String::trim("\xc5\x98e-", "\xc5\x98-") ); // "e" // Ře-



__halt_compiler();

------EXPECT------
string(1) "x"

NULL

string(3) "a b"

string(5) " a b "

string(1) "e"
