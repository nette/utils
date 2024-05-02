<?php

/**
 * Test: Nette\Utils\Strings::matchAll()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// not matched
Assert::same([], Strings::matchAll('hello world!', '#([E-L])+#'));


// sentinel
Assert::same([
	[''], [''], [''],
], Strings::matchAll('he', '##'));


// capturing
Assert::same([
	['hell', 'l'],
	['l', 'l'],
], Strings::matchAll('hello world!', '#([e-l])+#'));

Assert::same([
	['hell'],
	['l'],
], Strings::matchAll('hello world!', '#[e-l]+#'));


// options
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
	[['lu', 1], ['l', 1], ['u', 2]],
	[['ou', 4], ['o', 4], ['u', 5]],
	[['k', 7], ['k', 7], ['', 8]],
	[['k', 10], ['k', 10], ['', 11]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true, utf8: true));

Assert::same([
	[['lu', 2], ['ou', 6], ['k', 10], ['k', 14]],
	[['l', 2], ['o', 6], ['k', 10], ['k', 14]],
	[['u', 3], ['u', 7], ['', 11], ['', 15]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true, patternOrder: true));

Assert::same([
	[['lu', 1], ['ou', 4], ['k', 7], ['k', 10]],
	[['l', 1], ['o', 4], ['k', 7], ['k', 10]],
	[['u', 2], ['u', 5], ['', 8], ['', 11]],
], Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true, patternOrder: true, utf8: true));

Assert::same([['l'], ['k'], ['k']], Strings::matchAll('žluťoučký kůň', '#[e-l]+#u', offset: 2));

Assert::same([['k'], ['k']], Strings::matchAll('žluťoučký kůň', '#[e-l]+#u', offset: 2, utf8: true));

Assert::same([['žluťoučký'], ['kůň']], Strings::matchAll('žluťoučký kůň', '#\w+#', utf8: true)); // without modifier

Assert::same([['ll', 'l']], Strings::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2));
Assert::same([['ll', 'l']], Strings::matchAll('hello world!', '#[e-l]+#', offset: 2, patternOrder: true));

Assert::same([['e', null]], Strings::matchAll('hello world!', '#e(x)*#', unmatchedAsNull: true));


// right edge
Assert::same([['']], Strings::matchAll('he', '#(?<=e)#', offset: 2));
Assert::same([], Strings::matchAll('he', '#(?<=x)#', offset: 2));
Assert::same([], Strings::matchAll('he', '##', offset: 3));
