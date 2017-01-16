<?php

/**
 * Test: Nette\Utils\Arrays::grep() errors.
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	Arrays::grep(['a', '1', 'c'], '#*#');
}, Nette\Utils\RegexpException::class, 'Compilation failed: nothing to repeat at offset 0 in pattern: #*#');


Assert::exception(function () {
	Arrays::grep(['a', "1\xFF", 'c'], '#\d#u');
}, Nette\Utils\RegexpException::class, 'Malformed UTF-8 data (pattern: #\d#u)');
