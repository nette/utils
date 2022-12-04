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
	[['lu', 2], ['l', 2], ['u', 3]],
	[['ou', 6], ['o', 6], ['u', 7]],
	[['k', 10], ['k', 10], ['', 11]],
	[['k', 14], ['k', 14], ['', 15]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true));

Assert::same([
	[['lu', 2], ['ou', 6], ['k', 10], ['k', 14]],
	[['l', 2], ['o', 6], ['k', 10], ['k', 14]],
	[['u', 3], ['u', 7], ['', 11], ['', 15]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER));

Assert::same([
	[['lu', 2], ['ou', 6], ['k', 10], ['k', 14]],
	[['l', 2], ['o', 6], ['k', 10], ['k', 14]],
	[['u', 3], ['u', 7], ['', 11], ['', 15]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true, patternOrder: true));

Assert::same([['l'], ['k'], ['k']], Strings::matchAll('žluťoučký kůň', '#[e-l]+#u', offset: 2));

Assert::same([['ll', 'l']], Strings::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2));
Assert::same([['ll', 'l']], Strings::matchAll('hello world!', '#[e-l]+#', offset: 2, patternOrder: true));

Assert::same([['e', null]], Strings::matchAll('hello world!', '#e(x)*#', unmatchedAsNull: true));

Assert::same([], Strings::matchAll('hello world!', '', offset: 50));
