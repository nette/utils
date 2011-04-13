<?php

/**
 * Test: Nette\StringUtils and error in callback.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same('HELLO', StringUtils::replace('hello', '#.#', function($m) {
	$a++; // E_NOTICE
	return strtoupper($m[0]);
}));
