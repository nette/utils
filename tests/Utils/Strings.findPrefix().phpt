<?php

/**
 * Test: Nette\Utils\Strings::findPrefix()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Strings::findPrefix(["\xC0\x80", "\xC1\x80"]));
Assert::same('', Strings::findPrefix(["\xC0\x80", "\xC0\x81"]));
Assert::same('', Strings::findPrefix(["\xC0\x80\x80", "\xC0\x80\x81"]));
Assert::same('', Strings::findPrefix(["\xC0\x80\x80\x80", "\xC0\x80\x80\x81"]));
Assert::same('', Strings::findPrefix(['', '']));
Assert::same('', Strings::findPrefix(['a', '']));
Assert::same('', Strings::findPrefix(['', 'b']));
Assert::same('', Strings::findPrefix(['a', 'b']));
Assert::same('a', Strings::findPrefix(['a', 'a']));
Assert::same('a', Strings::findPrefix(['aa', 'a']));
Assert::same('a', Strings::findPrefix(['a', 'ab']));
Assert::same('a', Strings::findPrefix(['aa', 'ab']));
Assert::same('ab', Strings::findPrefix(['ab', 'ab']));

Assert::same("I\u{F1}e", Strings::findPrefix(["I\u{F1}e", "I\u{F1}e"]));
Assert::same("I\u{F1}", Strings::findPrefix(["I\u{F1}", "I\u{F1}"]));
Assert::same('I', Strings::findPrefix(["I\u{F2}", "I\u{F1}"]));
Assert::same('I', Strings::findPrefix(["I\u{131}", "I\u{F1}"]));

Assert::same('', Strings::findPrefix(['', '']));
Assert::same('', Strings::findPrefix(['', '', '']));
Assert::same('', Strings::findPrefix(['a', '', '']));
Assert::same('ab', Strings::findPrefix(['ab1', 'ab', 'ab2']));
