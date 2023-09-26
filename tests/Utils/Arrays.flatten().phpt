<?php

/**
 * Test: Nette\Utils\Arrays::flatten()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$res = Arrays::flatten([
	2 => ['a', ['b']],
	4 => ['c', 'd'],
	'e',
]);

Assert::same([
	0 => 'a',
	1 => 'b',
	2 => 'c',
	3 => 'd',
	4 => 'e',
], $res);

$res = Arrays::flatten([
	5 => 'a',
	10 => [
		'z' => 'b',
		1 => 'c',
	],
	'y' => 'd',
	'z' => 'e',
], preserveKeys: true);

Assert::same([
	5 => 'a',
	'z' => 'e',
	1 => 'c',
	'y' => 'd',
], $res);
