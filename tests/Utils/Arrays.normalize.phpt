<?php

/**
 * Test: Nette\Utils\Arrays::searchKey()
 */

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(
	[
		'first' => NULL,
		'a' => 'second',
		'd' => ['third'],
		'fourth' => NULL,
	],
	Arrays::normalize([
		1 => 'first',
		'a' => 'second',
		'd' => ['third'],
		7 => 'fourth',
	])
);


Assert::same(
	[
		'first' => TRUE,
		'' => 'second',
	],
	Arrays::normalize([
		1 => 'first',
		'' => 'second',
	], TRUE)
);
