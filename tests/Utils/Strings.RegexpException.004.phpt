<?php

/**
 * Test: Nette\Utils\Strings and error in callback.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



Assert::same('HELLO', Strings::replace('hello', '#.#', callback(function($m) {
	$a++; // E_NOTICE
	return strtoupper($m[0]);
})));
