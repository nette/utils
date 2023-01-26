<?php

/**
 * Test: Nette\Utils\FileSystem rename()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('rename file & dir', function () {
	FileSystem::write(getTempDir() . '/8/file', 'Hello');
	FileSystem::rename(getTempDir() . '/8', getTempDir() . '/9');
	FileSystem::rename(getTempDir() . '/9/file', getTempDir() . '/9/x/file');
	Assert::same('Hello', FileSystem::read(getTempDir() . '/9/x/file'));
});

test('overwrite file', function () {
	FileSystem::write(getTempDir() . '/8/newfile', 'World');
	Assert::exception(function () {
		FileSystem::rename(getTempDir() . '/8/newfile', getTempDir() . '/9/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(getTempDir() . '/9/x/file'));

	FileSystem::rename(getTempDir() . '/8/newfile', getTempDir() . '/9/x/file');
	Assert::same('World', FileSystem::read(getTempDir() . '/9/x/file'));
});

test('overwrite dir', function () {
	FileSystem::createDir(getTempDir() . '/10/');
	Assert::exception(function () {
		FileSystem::rename(getTempDir() . '/10', getTempDir() . '/9', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('World', FileSystem::read(getTempDir() . '/9/x/file'));

	FileSystem::rename(getTempDir() . '/10', getTempDir() . '/9');
	Assert::false(file_exists(getTempDir() . '/9/x/file'));
	Assert::false(file_exists(getTempDir() . '/10'));
});

test('same name', function () {
	FileSystem::createDir(getTempDir() . '/11/');
	FileSystem::rename(getTempDir() . '/11', getTempDir() . '/11');
	Assert::true(file_exists(getTempDir() . '/11'));
	FileSystem::rename(getTempDir() . '/11', getTempDir() . '/11/');
	Assert::true(file_exists(getTempDir() . '/11'));
});

Assert::exception(function () {
	FileSystem::rename(getTempDir() . '/10', getTempDir() . '/9');
}, Nette\IOException::class, "File or directory '%S%' not found.");
