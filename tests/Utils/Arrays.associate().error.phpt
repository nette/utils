<?php

/**
 * Test: Nette\Utils\Arrays::associate()
 *
 * @author     David Grudl
 */

use Nette\Utils\Arrays,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


foreach (array('', '=', '=age', '=>', '|', '|name') as $path) {
	Assert::exception(function() use ($path) {
		Arrays::associate(array(), $path);
	}, 'Nette\InvalidArgumentException', "Invalid path '$path'.");
}
