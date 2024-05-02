<?php

/**
 * Test: Nette\Utils\Strings::matchAll()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// not matched
Assert::type(Generator::class, Strings::matchAll('hello world!', '#([E-L])+#', lazy: true));
Assert::same(0, iterator_count(Strings::matchAll('hello world!', '#([E-L])+#', lazy: true)));


// sentinel
Assert::same(
	[['h'], ['e']],
	iterator_to_array(Strings::matchAll('he', '#.#', lazy: true)),
);

Assert::same(
	[[''], ['']],
	iterator_to_array(Strings::matchAll('he', '##', lazy: true)),
);


// right edge
Assert::same(
	[['']],
	iterator_to_array(Strings::matchAll('he', '#(?<=e)#', offset: 2, lazy: true)),
);

Assert::same(
	[],
	iterator_to_array(Strings::matchAll('he', '#(?<=x)#', offset: 2, lazy: true)),
);

Assert::same(
	[],
	iterator_to_array(Strings::matchAll('he', '##', offset: 3, lazy: true)),
);


// capturing
Assert::same([
	['hell', 'l'],
	['l', 'l'],
], iterator_to_array(Strings::matchAll('hello world!', '#([e-l])+#', lazy: true)));

Assert::same([
	['hell'],
	['l'],
], iterator_to_array(Strings::matchAll('hello world!', '#[e-l]+#', lazy: true)));


// options
Assert::same([
	[['lu', 2], ['l', 2], ['u', 3]],
	[['ou', 6], ['o', 6], ['u', 7]],
	[['k', 10], ['k', 10], ['', 11]],
	[['k', 14], ['k', 14], ['', 15]],
], iterator_to_array(Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true, lazy: true)));

Assert::same([
	[['lu', 1], ['l', 1], ['u', 2]],
	[['ou', 4], ['o', 4], ['u', 5]],
	[['k', 7], ['k', 7], ['', 8]],
	[['k', 10], ['k', 10], ['', 11]],
], iterator_to_array(Strings::matchAll('žluťoučký kůň!', '#([a-z])([a-z]*)#u', captureOffset: true, utf8: true, lazy: true)));

Assert::same(
	[['l'], ['k'], ['k']],
	iterator_to_array(Strings::matchAll('žluťoučký kůň', '#[e-l]+#u', offset: 2, lazy: true)),
);

Assert::same(
	[['k'], ['k']],
	iterator_to_array(Strings::matchAll('žluťoučký kůň', '#[e-l]+#u', offset: 2, utf8: true, lazy: true)),
);

Assert::same(
	[['e', null]],
	iterator_to_array(Strings::matchAll('hello world!', '#e(x)*#', unmatchedAsNull: true, lazy: true)),
);
