<?php

/**
 * Test: Nette\Utils\Arrays::grep()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same([
	1 => '1',
], Arrays::grep(['a', '1', 'c'], '#\d#'));

Assert::same([
	0 => 'a',
	2 => 'c',
], Arrays::grep(['a', '1', 'c'], '#\d#', PREG_GREP_INVERT));

Assert::same([
	0 => 'a',
	2 => 'c',
], Arrays::grep(['a', '1', 'c'], '#\d#', invert: true));
