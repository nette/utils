<?php

/**
 * Test: Nette\Utils\Strings::after()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('extracts substring after a delimiter with occurrence parameter', function () {
	$foo = '0123456789a123456789b123456789c';
	Assert::same('123456789a123456789b123456789c', Strings::after($foo, '0', 1));
	Assert::same('a123456789b123456789c', Strings::after($foo, '9', 1));
	Assert::same('a123456789b123456789c', Strings::after($foo, '789', 1));
	Assert::same('0123456789a123456789b123456789c', Strings::after($foo, '', 1));
	Assert::same('', Strings::after($foo, '', -1));
	Assert::same('', Strings::after($foo, 'c', -1));
	Assert::same('c', Strings::after($foo, '9', -1));
	Assert::same('c', Strings::after($foo, '789', -1));
	Assert::same('c', Strings::after($foo, '9', 3));
	Assert::same('c', Strings::after($foo, '789', 3));
	Assert::same('a123456789b123456789c', Strings::after($foo, '9', -3));
	Assert::same('a123456789b123456789c', Strings::after($foo, '789', -3));
	Assert::null(Strings::after($foo, '9', 0));
	Assert::null(Strings::after($foo, 'not-in-string'));
	Assert::null(Strings::after($foo, 'b', -2));
	Assert::null(Strings::after($foo, 'b', 2));
});


test('handles Unicode strings in substring extraction after delimiter', function () {
	$foo = "I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n"; // Iñtërnâtiônàlizætiøn
	Assert::same("\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n", Strings::after($foo, 'ti', 1));
	Assert::same("\u{F8}n", Strings::after($foo, 'ti', 2));
});
