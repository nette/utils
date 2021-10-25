<?php

/**
 * Test: Nette\Utils\Strings::matchAll()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same([], Strings::matchAll('hello world!', '#([E-L])+#'));

Assert::same([
	['hell', 'l'],
	['l', 'l'],
], Strings::matchAll('hello world!', '#([e-l])+#'));

Assert::same([
	['hell'],
	['l'],
], Strings::matchAll('hello world!', '#[e-l]+#'));

Assert::same([
	[['lu', 2], ['l', 2], ['u', 3]],
	[['ou', 6], ['o', 6], ['u', 7]],
	[['k', 10], ['k', 10], ['', 11]],
	[['k', 14], ['k', 14], ['', 15]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', PREG_OFFSET_CAPTURE));

Assert::same([
	[['lu', 2], ['ou', 6], ['k', 10], ['k', 14]],
	[['l', 2], ['o', 6], ['k', 10], ['k', 14]],
	[['u', 3], ['u', 7], ['', 11], ['', 15]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER));

Assert::same([['l'], ['k'], ['k']], Strings::matchAll('žluťoučký kůň', '#[e-l]+#u', 0, 2));

Assert::same([['ll', 'l']], Strings::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2));

Assert::same([], Strings::matchAll('hello world!', '', 0, 50));
