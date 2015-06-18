<?php

/**
 * Test: Nette\Utils\Strings::indexOf()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
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
	Assert::false(Strings::indexOf($foo, '9', 0));
	Assert::false(Strings::indexOf($foo, 'not-in-string'));
	Assert::false(Strings::indexOf($foo, 'b', -2));
	Assert::false(Strings::indexOf($foo, 'b', 2));
});


test(function () {
	$foo = "I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n"; // Iñtërnâtiônàlizætiøn
	Assert::same(7, Strings::indexOf($foo, 'ti', 1));
	Assert::same(16, Strings::indexOf($foo, 'ti', 2));
	Assert::same(3, Strings::indexOf($foo, "\xc3\xab"));
});
