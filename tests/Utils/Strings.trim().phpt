<?php

/**
 * Test: Nette\Utils\Strings::trim()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('x', Strings::trim(" \t\n\r\x00\x0B\u{A0}x"));
Assert::same('a b', Strings::trim(' a b '));
Assert::same(' a b ', Strings::trim(' a b ', ''));
Assert::same('e', Strings::trim("\u{158}e-", "\u{158}-")); // Ře-
Assert::same('foo', Strings::trim("\u{2000}\u{200B}foo"));

Assert::exception(
	fn() => Strings::trim("\xC2x\xA0"),
	Nette\Utils\RegexpException::class,
	null,
);
