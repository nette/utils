<?php

/**
 * Test: Nette\Utils\Arrays::associate()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


foreach (['', '=', '=age', '=>', '|', '|name'] as $path) {
	Assert::exception(
		fn() => Arrays::associate([], $path),
		Nette\InvalidArgumentException::class,
		"Invalid path '$path'.",
	);
}
