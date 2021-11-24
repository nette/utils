<?php

/**
 * Test: Nette\Utils\Strings::split()
 */

declare(strict_types=1);

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

Assert::same([
	'a',
	',',
	'b',
	',',
	'c',
], Strings::split('a, b, c', '#(,)\s*#', skipEmpty: true));

Assert::same([
	['a', 0],
	[',', 1],
	['b', 3],
	[',', 4],
	['c', 6],
], Strings::split('a, b, c', '#(,)\s*#', PREG_SPLIT_OFFSET_CAPTURE));

Assert::same([
	['ž', 0],
	['lu', 2],
	['ť', 4],
	['ou', 6],
	['č', 8],
	['k', 10],
	['ý ', 11],
	['k', 14],
	['ůň', 15],
], Strings::split('žluťoučký kůň', '#([a-z]+)\s*#u', captureOffset: true));

Assert::same([
	['ž', 0],
	['lu', 1],
	['ť', 3],
	['ou', 4],
	['č', 6],
	['k', 7],
	['ý ', 8],
	['k', 10],
	['ůň', 11],
], Strings::split('žluťoučký kůň', '#([a-z]+)\s*#u', captureOffset: true, utf8: true));

Assert::same(['', ' ', ''], Strings::split('žluťoučký kůň', '#\w+#', utf8: true)); // without modifier

Assert::same(['a', ',', 'b, c'], Strings::split('a, b, c', '#(,)\s*#', limit: 2));
