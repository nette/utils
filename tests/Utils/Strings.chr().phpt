<?php

/**
 * Test: Nette\Utils\Strings::chr()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same("\x00", Strings::chr(0x000000));
Assert::same("\x7F", Strings::chr(0x00007F));
Assert::same("\u{80}", Strings::chr(0x000080));
Assert::same("\u{7FF}", Strings::chr(0x0007FF));
Assert::same("\u{800}", Strings::chr(0x000800));
Assert::same("\u{D7FF}", Strings::chr(0x00D7FF));
Assert::same("\u{E000}", Strings::chr(0x00E000));
Assert::same("\u{FFFF}", Strings::chr(0x00FFFF));
Assert::same("\u{10000}", Strings::chr(0x010000));
Assert::same("\u{10FFFF}", Strings::chr(0x10FFFF));

foreach ([-1, 0xD800, 0xDFFF, 0x110000] as $code) {
	Assert::exception(
		fn() => Strings::chr($code),
		Nette\InvalidArgumentException::class,
		'Code point must be in range 0x0 to 0xD7FF or 0xE000 to 0x10FFFF.',
	);
}
