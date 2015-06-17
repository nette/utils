<?php

/**
 * Test: Nette\Utils\Strings::before()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
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
	Assert::false(Strings::before($foo, '9', 0));
	Assert::false(Strings::before($foo, 'not-in-string'));
	Assert::false(Strings::before($foo, 'b', -2));
	Assert::false(Strings::before($foo, 'b', 2));
});


test(function () {
	$foo = "I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n"; // Iñtërnâtiônàlizætiøn
	Assert::same("I\xc3\xb1t\xc3\xabrn\xc3\xa2", Strings::before($foo, 'ti', 1));
	Assert::same("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6", Strings::before($foo, 'ti', 2));
});
