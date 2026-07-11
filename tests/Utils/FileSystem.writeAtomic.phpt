<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\FileSystem writeAtomic()
 */

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('writes a new file including parent directories', function () {
	$file = getTempDir() . '/1/writeAtomic/file.txt';
	FileSystem::writeAtomic($file, 'Hello');
	Assert::same('Hello', FileSystem::read($file));
});

test('overwrites an existing file', function () {
	$file = getTempDir() . '/2/file.txt';
	FileSystem::write($file, 'old content');
	FileSystem::writeAtomic($file, 'new');
	Assert::same('new', FileSystem::read($file));
});

test('leaves no temporary files behind', function () {
	$dir = getTempDir() . '/3';
	FileSystem::writeAtomic($dir . '/file.txt', 'Hello');
	Assert::same(['file.txt'], array_map(
		fn(SplFileInfo $item) => $item->getFilename(),
		iterator_to_array(new FilesystemIterator($dir), preserve_keys: false),
	));
});

test('throws and cleans up the temporary file when the target cannot be replaced', function () {
	$dir = getTempDir() . '/4';
	FileSystem::createDir($dir . '/target'); // a directory in the way of the target file
	Assert::exception(
		fn() => FileSystem::writeAtomic($dir . '/target', 'Hello'),
		Nette\IOException::class,
		"Unable to write file '%a%target'. %a%",
	);
	Assert::same(['target'], array_map(
		fn(SplFileInfo $item) => $item->getFilename(),
		iterator_to_array(new FilesystemIterator($dir), preserve_keys: false),
	));
});
