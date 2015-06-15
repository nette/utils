<?php

/**
 * Test: Nette\Utils\Strings::before()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';



test(function () { //before
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
