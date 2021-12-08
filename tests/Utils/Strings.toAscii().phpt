<?php

/**
 * Test: Nette\Utils\Strings::toAscii()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('ZLUTOUCKY KUN oeooo--', Strings::toAscii("\u{17D}LU\u{164}OU\u{10C}K\u{DD} K\u{16E}\u{147} \u{F6}\u{151}\u{F4}o\x2d\u{2013}")); // ŽLUŤOUČKÝ KŮŇ öőôo
Assert::same('Zlutoucky kun', Strings::toAscii("Z\u{30C}lut\u{30C}ouc\u{30C}ky\u{301} ku\u{30A}n\u{30C}")); // Žluťoučký kůň with combining characters
Assert::same('Z `\'"^~?', Strings::toAscii("\u{17D} `'\"^~?"));
Assert::same('"""\'\'\'>><<^', Strings::toAscii("\u{201E}\u{201C}\u{201D}\u{201A}\u{2018}\u{2019}\u{BB}\u{AB}\u{B0}")); // „“”‚‘’»«°
Assert::same('', Strings::toAscii("\u{10000}")); // U+10000
Assert::same('', Strings::toAscii("\u{A4}")); // non-ASCII char
Assert::same('a b', Strings::toAscii("a\u{A0}b")); // non-breaking space
Assert::same('Tarikh', Strings::toAscii("Ta\u{2BE}rikh")); // Taʾrikh
Assert::exception(function () {
	Strings::toAscii("0123456789\xFF");
}, Nette\Utils\RegexpException::class, null, PREG_BAD_UTF8_ERROR);

if (class_exists('Transliterator') && Transliterator::create('Any-Latin; Latin-ASCII')) {
	Assert::same('Athena->Moskva', Strings::toAscii("\u{391}\u{3B8}\u{3AE}\u{3BD}\u{3B1}\u{2192}\u{41C}\u{43E}\u{441}\u{43A}\u{432}\u{430}")); // Αθήνα→Москва
}


Assert::same('Ya ya Yu yu', Strings::toAscii("\u{42F} \u{44F} \u{42E} \u{44E}")); // Я я Ю ю
Assert::same('Ae Oe Ue Ss ae oe ue ss', Strings::toAscii("\u{c4} \u{d6} \u{dc} \u{1e9e} \u{e4} \u{f6} \u{fc} \u{df}")); // Ä Ö Ü ẞ ä ö ü ß
