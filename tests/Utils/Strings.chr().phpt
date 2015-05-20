<?php

/**
 * Test: Nette\Utils\Strings::chr()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "\x00", Strings::chr(0x000000) );
Assert::same( "\x7F", Strings::chr(0x00007F) );
Assert::same( "\xC2\x80", Strings::chr(0x000080) );
Assert::same( "\xDF\xBF", Strings::chr(0x0007FF) );
Assert::same( "\xE0\xA0\x80", Strings::chr(0x000800) );
Assert::same( "\xED\x9F\xBF", Strings::chr(0x00D7FF) );
Assert::same( "\xEE\x80\x80", Strings::chr(0x00E000) );
Assert::same( "\xEF\xBF\xBF", Strings::chr(0x00FFFF) );
Assert::same( "\xF0\x90\x80\x80", Strings::chr(0x010000) );
Assert::same( "\xF4\x8F\xBF\xBF", Strings::chr(0x10FFFF) );

foreach ([-1, 0xD800, 0xDFFF, 0x110000] as $code) {
	Assert::exception(function() use ($code) {
		Strings::chr($code);
	}, 'Nette\InvalidArgumentException', 'Code point must be in range 0x0 to 0xD7FF or 0xE000 to 0x10FFFF.');
}
