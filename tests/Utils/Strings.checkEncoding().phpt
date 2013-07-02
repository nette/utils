<?php

/**
 * Test: Nette\Utils\Strings::checkEncoding()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::true( Strings::checkEncoding("\xc5\xbelu\xc5\xa5ou\xc4\x8dk\xc3\xbd") ); // UTF-8   žluťoučký
Assert::true( Strings::checkEncoding("\x01") ); // C0
Assert::false( Strings::checkEncoding("\xed\xa0\x80") ); // surrogate pairs   xD800
Assert::false( Strings::checkEncoding("\xf4\x90\x80\x80") ); // out of range   x110000
