<?php

/**
 * Test: Nette\Utils\Strings::normalize()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same("Hello\n  World", Strings::normalize("\r\nHello  \r  World \n\n"));

Assert::same('Hello  World', Strings::normalize("Hello \x00 World"));
Assert::same('Hello  World', Strings::normalize("Hello \x0B World"));
Assert::same('Hello  World', Strings::normalize("Hello \x1F World"));
Assert::same("Hello \x7E World", Strings::normalize("Hello \x7E World"));
Assert::same('Hello  World', Strings::normalize("Hello \x7F World"));
Assert::same('Hello  World', Strings::normalize("Hello \u{80} World"));
Assert::same('Hello  World', Strings::normalize("Hello \u{9F} World"));
Assert::same("Hello \u{A0} World", Strings::normalize("Hello \u{A0} World"));

if (class_exists('Normalizer')) {
	Assert::same("\xC3\x85", Strings::normalize("\xC3\x85")); // NFC -> NFC form
	Assert::same("\xC3\x85", Strings::normalize("A\xCC\x8A")); // NFD -> NFC form
}
