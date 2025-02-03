<?php

/**
 * Test: Nette\Utils\Strings::before()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('extracts substring before a delimiter with occurrence parameter', function () {
	$foo = '0123456789a123456789b123456789c';
	Assert::same('', Strings::before($foo, '0', 1));
	Assert::same('012345678', Strings::before($foo, '9', 1));
	Assert::same('0123456', Strings::before($foo, '789', 1));
	Assert::same('', Strings::before($foo, '', 1));
	Assert::same('0123456789a123456789b123456789c', Strings::before($foo, '', -1));
	Assert::same('0123456789a123456789b123456789', Strings::before($foo, 'c', -1));
	Assert::same('0123456789a123456789b123456', Strings::before($foo, '789', -1));
	Assert::same('0123456789a123456789b12345678', Strings::before($foo, '9', 3));
	Assert::same('0123456789a123456789b123456', Strings::before($foo, '789', 3));
	Assert::same('012345678', Strings::before($foo, '9', -3));
	Assert::same('0123456', Strings::before($foo, '789', -3));
	Assert::null(Strings::before($foo, '9', 0));
	Assert::null(Strings::before($foo, 'not-in-string'));
	Assert::null(Strings::before($foo, 'b', -2));
	Assert::null(Strings::before($foo, 'b', 2));
});


test('processes Unicode strings in substring extraction before delimiter', function () {
	$foo = "I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n"; // Iñtërnâtiônàlizætiøn
	Assert::same("I\u{F1}t\u{EB}rn\u{E2}", Strings::before($foo, 'ti', 1));
	Assert::same("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}", Strings::before($foo, 'ti', 2));
});
