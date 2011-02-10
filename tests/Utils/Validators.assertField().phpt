<?php

/**
 * Test: Nette\Utils\Validators::assertField()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Validators;



require __DIR__ . '/../bootstrap.php';


$arr = array('first' => TRUE);

Assert::throws(function() use ($arr) {
	Validators::assertField($arr, 'second', 'int');
}, 'Nette\Utils\AssertionException', "Missing field 'second' in array.");

Validators::assertField($arr, 'first');

Assert::throws(function() use ($arr) {
	Validators::assertField($arr, 'first', 'int');
}, 'Nette\Utils\AssertionException', "The field 'first' expects to be int, boolean given.");
