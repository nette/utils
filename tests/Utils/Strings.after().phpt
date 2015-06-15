<?php

/**
 * Test: Nette\Utils\Strings::after()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';



test(function () { //after
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
