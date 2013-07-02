<?php

/**
 * Test: Nette\Utils\Validators::assert()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Validators;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function() {
	Validators::assert(TRUE, 'int');
}, 'Nette\Utils\AssertionException', "The variable expects to be int, boolean given.");

Assert::exception(function() {
	Validators::assert('1.0', 'int|float');
}, 'Nette\Utils\AssertionException', "The variable expects to be int or float, string '1.0' given.");

Assert::exception(function() {
	Validators::assert(1, 'string|integer:2..5', 'variable');
}, 'Nette\Utils\AssertionException', "The variable expects to be string or integer in range 2..5, integer given.");
