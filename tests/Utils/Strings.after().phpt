<?php

/**
 * Test: Nette\Utils\Strings::after()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
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
	Assert::false(Strings::after($foo, '9', 0));
	Assert::false(Strings::after($foo, 'not-in-string'));
	Assert::false(Strings::after($foo, 'b', -2));
	Assert::false(Strings::after($foo, 'b', 2));
});


test(function () {
	$foo = "I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n"; // Iñtërnâtiônàlizætiøn
	Assert::same("\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n", Strings::after($foo, 'ti', 1));
	Assert::same("\xc3\xb8n", Strings::after($foo, 'ti', 2));
});
