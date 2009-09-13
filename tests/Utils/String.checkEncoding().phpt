<?php

/**
 * Test: String::checkEncoding()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::checkEncoding("\xc5\xbelu\xc5\xa5ou\xc4\x8dk\xc3\xbd"), 'UTF-8' ); // True // žluťoučký
dump( String::checkEncoding("\x01"), 'C0' ); // True
dump( String::checkEncoding("\xed\xa0\x80"), 'surrogate pairs' ); // False // xD800
dump( String::checkEncoding("\xef\xbb\xbf"), 'noncharacter' ); // False // xFEFF
dump( String::checkEncoding("\xf4\x90\x80\x80"), 'out of range' ); // False // x110000



__halt_compiler();

------EXPECT------
UTF-8: bool(TRUE)

C0: bool(TRUE)

surrogate pairs: bool(FALSE)

noncharacter: bool(FALSE)

out of range: bool(FALSE)
