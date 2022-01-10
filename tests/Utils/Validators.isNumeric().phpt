<?php

/**
 * Test: Nette\Utils\Validators::isNumeric()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('Valid numbers by string', function () {
	Assert::true(Validators::isNumeric('1.0'));
	Assert::true(Validators::isNumeric('1'));
	Assert::true(Validators::isNumeric('-1'));
	Assert::true(Validators::isNumeric('+1'));
	Assert::true(Validators::isNumeric('.0'));
	Assert::true(Validators::isNumeric('1.'));
	Assert::true(Validators::isNumeric('01.10'));
});


test('Valid numbers by float', function () {
	Assert::true(Validators::isNumeric(1.0));
	Assert::true(Validators::isNumeric(.0));
	Assert::true(Validators::isNumeric(1.));
});


test('Valid numbers by int', function () {
	Assert::true(Validators::isNumeric(1));
	Assert::true(Validators::isNumeric(-1));
	Assert::true(Validators::isNumeric(+1));
});


test('Invalid numbers', function () {
	Assert::false(Validators::isNumeric('.')); // it is not 0.0
	Assert::false(Validators::isNumeric(' 1'));
	Assert::false(Validators::isNumeric('1 '));
	Assert::false(Validators::isNumeric('- 1'));
});
