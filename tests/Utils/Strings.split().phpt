<?php

/**
 * Test: Nette\Utils\Strings::split()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same([
	'a',
	',',
	'b',
	',',
	'c',
], Strings::split('a, b, c', '#(,)\s*#'));

Assert::same([
	'a',
	',',
	'b',
	',',
	'c',
], Strings::split('a, b, c', '#(,)\s*#', PREG_SPLIT_NO_EMPTY));
