<?php

/**
 * Test: Nette\Utils\Strings::matchAll()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( [], Strings::matchAll('hello world!', '#([E-L])+#') );

Assert::same( [
	['hell', 'l'],
	['l', 'l'],
], Strings::matchAll('hello world!', '#([e-l])+#') );

Assert::same( [
	['hell'],
	['l'],
], Strings::matchAll('hello world!', '#[e-l]+#') );

Assert::same( [
	[
		['hell', 0],
	],
	[
		['l', 9],
	],
], Strings::matchAll('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( [['ll', 'l']], Strings::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2) );
