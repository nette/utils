<?php

/**
 * Test: Nette\Utils\Finder errors.
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('missing folder', function () {
	Assert::exception(
		fn() => iterator_to_array(Finder::findFiles('*')->in('unknown')),
		Nette\InvalidStateException::class,
		"Directory 'unknown' does not exist.",
	);
});


test('absolute mask', function () {
	Assert::exception(
		fn() => iterator_to_array(Finder::findFiles('/*')->in('.')),
		Nette\InvalidStateException::class,
		"You cannot combine the absolute path in the mask '/*' and the directory to search '.'.",
	);
});


test('globing', function () {
	Assert::exception(
		fn() => iterator_to_array(Finder::findFiles('fixtures.finder/*/unknown/*')),
		Nette\InvalidStateException::class,
		"Directory './fixtures.finder/*/unknown' does not exist.",
	);
});
