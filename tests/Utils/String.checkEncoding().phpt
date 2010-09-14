<?php

/**
 * Test: Nette\String::checkEncoding()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



Assert::true( String::checkEncoding("\xc5\xbelu\xc5\xa5ou\xc4\x8dk\xc3\xbd"), 'UTF-8' ); // žluťoučký
Assert::true( String::checkEncoding("\x01"), 'C0' );
Assert::false( String::checkEncoding("\xed\xa0\x80"), 'surrogate pairs' ); // xD800
Assert::false( String::checkEncoding("\xef\xbb\xbf"), 'noncharacter' ); // xFEFF
Assert::false( String::checkEncoding("\xf4\x90\x80\x80"), 'out of range' ); // x110000
