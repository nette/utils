<?php

/**
 * Test: Nette\Utils\Validators::isNumeric()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('handles numeric strings with decimals and signs', function () {
	Assert::true(Validators::isNumeric('1.0'));
	Assert::true(Validators::isNumeric('1'));
	Assert::true(Validators::isNumeric('-1'));
	Assert::true(Validators::isNumeric('+1'));
	Assert::true(Validators::isNumeric('.0'));
	Assert::true(Validators::isNumeric('1.'));
	Assert::true(Validators::isNumeric('01.10'));
});


test('processes numeric float values', function () {
	Assert::true(Validators::isNumeric(1.0));
	Assert::true(Validators::isNumeric(.0));
	Assert::true(Validators::isNumeric(1.));
});


test('processes integer values', function () {
	Assert::true(Validators::isNumeric(1));
	Assert::true(Validators::isNumeric(-1));
	Assert::true(Validators::isNumeric(+1));
});


test('rejects non-numeric formats and malformed numbers', function () {
	Assert::false(Validators::isNumeric('.')); // it is not 0.0
	Assert::false(Validators::isNumeric(' 1'));
	Assert::false(Validators::isNumeric('1 '));
	Assert::false(Validators::isNumeric('- 1'));
});
