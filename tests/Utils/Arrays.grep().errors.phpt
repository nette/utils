<?php

/**
 * Test: Nette\Utils\Arrays::grep() errors.
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => Arrays::grep(['a', '1', 'c'], '#*#'),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: #*#',
);


Assert::exception(
	fn() => Arrays::grep(['a', "1\xFF", 'c'], '#\d#u'),
	Nette\Utils\RegexpException::class,
	'Malformed UTF-8 characters, possibly incorrectly encoded (pattern: #\d#u)',
);
