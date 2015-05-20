<?php

/**
 * Test: Nette\Utils\Arrays::associate()
 */

use Nette\Utils\Arrays,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


foreach (['', '=', '=age', '=>', '|', '|name'] as $path) {
	Assert::exception(function() use ($path) {
		Arrays::associate([], $path);
	}, 'Nette\InvalidArgumentException', "Invalid path '$path'.");
}
