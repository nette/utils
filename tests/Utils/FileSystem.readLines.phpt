<?php

/**
 * Test: Nette\Utils\FileSystem readLines()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('readLines', function () {
	FileSystem::write(getTempDir() . '/2/file', "\r\nHello\nWorld");

	$lines = FileSystem::readLines(getTempDir() . '/2/file');
	Assert::type(Generator::class, $lines);
	Assert::same(['', 'Hello', 'World'], iterator_to_array($lines));

	$lines = FileSystem::readLines(getTempDir() . '/2/file', stripNewLines: false);
	Assert::type(Generator::class, $lines);
	Assert::same(["\r\n", "Hello\n", 'World'], iterator_to_array($lines));
});


Assert::exception(
	fn() => FileSystem::readLines('missing'),
	Nette\IOException::class,
	"Unable to open file 'missing'.%A%",
);
