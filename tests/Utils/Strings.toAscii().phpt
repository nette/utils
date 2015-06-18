<?php

/**
 * Test: Nette\Utils\Strings::toAscii()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('ZLUTOUCKY KUN oooo--', Strings::toAscii("\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o\x2d\xe2\x80\x93")); // ŽLUŤOUČKÝ KŮŇ öőôo
Assert::same('Zlutoucky kun', Strings::toAscii("Z\xCC\x8Clut\xCC\x8Couc\xCC\x8Cky\xCC\x81 ku\xCC\x8An\xCC\x8C")); // Žluťoučký kůň with combining characters
Assert::same('Z `\'"^~?', Strings::toAscii("\xc5\xbd `'\"^~?"));
Assert::same('"""\'\'\'>><<^', Strings::toAscii("\xE2\x80\x9E\xE2\x80\x9C\xE2\x80\x9D\xE2\x80\x9A\xE2\x80\x98\xE2\x80\x99\xC2\xBB\xC2\xAB\xC2\xB0")); // „“”‚‘’»«°
Assert::same('', Strings::toAscii("\xF0\x90\x80\x80")); // U+10000
Assert::same('', Strings::toAscii("\xC2\xA4")); // non-ASCII char
Assert::same('a b', Strings::toAscii("a\xC2\xA0b")); // non-breaking space
Assert::same('Tarikh', Strings::toAscii("Ta\xCA\xBErikh")); // Taʾrikh

if (class_exists('Transliterator') && \Transliterator::create('Any-Latin; Latin-ASCII')) {
	Assert::same('Athena->Moskva', Strings::toAscii("\xCE\x91\xCE\xB8\xCE\xAE\xCE\xBD\xCE\xB1\xE2\x86\x92\xD0\x9C\xD0\xBE\xD1\x81\xD0\xBA\xD0\xB2\xD0\xB0")); // Αθήνα→Москва
}
