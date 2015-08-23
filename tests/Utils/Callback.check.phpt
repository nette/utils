<?php

/**
 * Test: Nette\Utils\Callback::check()
 */

use Nette\Utils\Callback;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('trim', Callback::check('trim'));

Assert::same('undefined', Callback::check('undefined', TRUE));


Assert::exception(function () {
	Callback::check(123, TRUE);
}, Nette\InvalidArgumentException::class, 'Given value is not a callable type.');


Assert::exception(function () {
	Callback::check('undefined');
}, Nette\InvalidArgumentException::class, "Callback 'undefined' is not callable.");
