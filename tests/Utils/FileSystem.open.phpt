<?php

/**
 * Test: Nette\Utils\FileSystem open()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('open', function () {
	$f = FileSystem::open(__FILE__, 'r');
	Assert::type('resource', $f);
});

Assert::exception(
	fn() => FileSystem::open('missing', 'r'),
	Nette\IOException::class,
	"Unable to open file 'missing'.%A%",
);
