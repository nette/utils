<?php

/**
 * Test: Nette\Utils\Strings::fixEncoding()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


function utfChar($ord)
{
	if ($ord < 0x80) {
		return chr($ord);
	} elseif ($ord < 0x800) {
		return chr(($ord >> 6) + 0xC0) . chr(($ord & 63) + 0x80);
	} elseif ($ord < 0x10000) {
		return chr(($ord >> 12) + 0xE0) . chr((($ord >> 6) & 63) + 0x80) . chr(($ord & 63) + 0x80);
	} elseif ($ord < 0x200000) {
		return chr(($ord >> 18) + 0xF0) . chr((($ord >> 12) & 63) + 0x80) . chr((($ord >> 6) & 63) + 0x80) . chr(($ord & 63) + 0x80);
	}
}


// invalid
Assert::same( "\xC5\xBEa\x01b", Strings::fixEncoding("\xC5\xBE" . "a\x01b") );

// surrogates area
for ($i = 0xD800; $i <= 0xDFFF; $i++) {
	Assert::same( "ab", Strings::fixEncoding("a" . utfChar($i) . "b") );
}

// out of range
for ($i = 0x110000; $i < 0x200000; $i+=0x99) {
	Assert::same( "ab", Strings::fixEncoding("a" . utfChar($i) . "b") );
}

/* // noncharacters
for ($i = 0xFFFE; $i < 0x10FFFE; $i+=0x10000) {
	Assert::same( "ab", Strings::fixEncoding("a" . utfChar($i) . utfChar($i+1) . "b") );
}*/
