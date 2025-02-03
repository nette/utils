<?php

/**
 * Test: Nette\Utils\Strings::indexOf()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('finds position of a substring with occurrence and offset', function () {
	$foo = '0123456789a123456789b123456789c';
	Assert::same(0, Strings::indexOf($foo, '0', 1));
	Assert::same(9, Strings::indexOf($foo, '9', 1));
	Assert::same(7, Strings::indexOf($foo, '789', 1));
	Assert::same(0, Strings::indexOf($foo, '', 1));
	Assert::same(31, Strings::indexOf($foo, '', -1));
	Assert::same(30, Strings::indexOf($foo, 'c', -1));
	Assert::same(29, Strings::indexOf($foo, '9', -1));
	Assert::same(27, Strings::indexOf($foo, '789', -1));
	Assert::same(29, Strings::indexOf($foo, '9', 3));
	Assert::same(27, Strings::indexOf($foo, '789', 3));
	Assert::same(9, Strings::indexOf($foo, '9', -3));
	Assert::same(7, Strings::indexOf($foo, '789', -3));
	Assert::null(Strings::indexOf($foo, '9', 0));
	Assert::null(Strings::indexOf($foo, 'not-in-string'));
	Assert::null(Strings::indexOf($foo, 'b', -2));
	Assert::null(Strings::indexOf($foo, 'b', 2));
	Assert::null(Strings::indexOf('', 'a', 1));
	Assert::null(Strings::indexOf('', 'a', -1));
});


test('determines substring position within a Unicode string', function () {
	$foo = "I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n"; // Iñtërnâtiônàlizætiøn
	Assert::same(7, Strings::indexOf($foo, 'ti', 1));
	Assert::same(16, Strings::indexOf($foo, 'ti', 2));
	Assert::same(3, Strings::indexOf($foo, "\u{EB}"));
});
