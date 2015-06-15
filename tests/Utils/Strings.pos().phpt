<?php

/**
 * Test: Nette\Utils\Strings::pos()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';



test(function () { //after
	$foo = '0123456789a123456789b123456789c';
	Assert::same(0, Strings::pos($foo, '0', 1));
	Assert::same(9, Strings::pos($foo, '9', 1));
	Assert::same(7, Strings::pos($foo, '789', 1));
	Assert::same(0, Strings::pos($foo, '', 1));
	Assert::same(31, Strings::pos($foo, '', -1));
	Assert::same(30, Strings::pos($foo, 'c', -1));
	Assert::same(29, Strings::pos($foo, '9', -1));
	Assert::same(27, Strings::pos($foo, '789', -1));
	Assert::same(29, Strings::pos($foo, '9', 3));
	Assert::same(27, Strings::pos($foo, '789', 3));
	Assert::same(9, Strings::pos($foo, '9', -3));
	Assert::same(7, Strings::pos($foo, '789', -3));
	Assert::false(Strings::pos($foo, '9', 0));
	Assert::false(Strings::pos($foo, 'not-in-string'));
	Assert::false(Strings::pos($foo, 'b', -2));
	Assert::false(Strings::pos($foo, 'b', 2));
});
