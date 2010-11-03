<?php

/**
 * Test: Nette\String and error in callback.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../bootstrap.php';



Assert::same('HELLO', String::replace('hello', '#.#', function($m) {
	$a++; // E_NOTICE
	return strtoupper($m[0]);
}));
