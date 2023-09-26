<?php

/**
 * Test: Nette\Utils\Strings::compare()
 * @phpExtension mbstring
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Strings::compare('', ''));
Assert::true(Strings::compare('', '', 0));
Assert::true(Strings::compare('', '', 1));
Assert::false(Strings::compare('xy', 'xx'));
Assert::true(Strings::compare('xy', 'xx', 0));
Assert::true(Strings::compare('xy', 'xx', 1));
Assert::false(Strings::compare('xy', 'yy', 1));
Assert::true(Strings::compare('xy', 'yy', -1));
Assert::true(Strings::compare('xy', 'yy', -1));
Assert::true(Strings::compare("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n", "I\u{D1}T\u{CB}RN\u{C2}TI\u{D4}N\u{C0}LIZ\u{C6}TI\u{D8}N")); // Iñtërnâtiônàlizætiøn
Assert::true(Strings::compare("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n", "I\u{D1}T\u{CB}RN\u{C2}TI\u{D4}N\u{C0}LIZ\u{C6}TI\u{D8}N", 10));

if (class_exists('Normalizer')) {
	Assert::true(Strings::compare("\xC3\x85", "A\xCC\x8A"), 'comparing NFC with NFD form');
	Assert::true(Strings::compare("A\xCC\x8A", "\xC3\x85"), 'comparing NFD with NFC form');
}
