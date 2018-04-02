<?php

/**
 * Test: Nette\Utils\FileSystem basic usage.
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class RemoteStream /* extends \streamWrapper */
{
	public function stream_open()
	{
		return true;
	}


	public function url_stat()
	{
		return false;
	}
}

stream_wrapper_register('remote', RemoteStream::class, STREAM_IS_URL);


test(function () { // createDir
	FileSystem::createDir(TEMP_DIR . '/1/b/');
	Assert::true(is_dir(TEMP_DIR . '/1/b'));

	FileSystem::createDir(TEMP_DIR . '/1/');
});

Assert::exception(function () {
	FileSystem::createDir('');
}, Nette\IOException::class, "Unable to create directory ''.%A%");


test(function () { // write + read
	FileSystem::write(TEMP_DIR . '/2/file', 'Hello');
	Assert::true(is_file(TEMP_DIR . '/2/file'));
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/2/file'));
});

Assert::exception(function () {
	FileSystem::write('', 'Hello');
}, Nette\IOException::class, "Unable to create directory ''.%A%");

Assert::exception(function () {
	FileSystem::read('');
}, Nette\IOException::class, "Unable to read file ''.");


test(function () { // copy
	Assert::false(stream_is_local('remote://example.com'));

	FileSystem::write(TEMP_DIR . '/3/file', 'Hello');

	FileSystem::copy(TEMP_DIR . '/3/file', TEMP_DIR . '/3/x/file');
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/3/x/file'));

	FileSystem::copy('remote://example.com', TEMP_DIR . '/3/x/y/file');
	Assert::true(is_file(TEMP_DIR . '/3/x/y/file'));

	FileSystem::write(TEMP_DIR . '/5/newfile', 'World');

	Assert::exception(function () {
		FileSystem::copy(TEMP_DIR . '/5/newfile', TEMP_DIR . '/3/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/3/x/file'));

	Assert::exception(function () {
		FileSystem::copy('remote://example.com', TEMP_DIR . '/3/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/3/x/file'));

	FileSystem::copy(TEMP_DIR . '/5/newfile', TEMP_DIR . '/3/x/file');
	Assert::same('World', FileSystem::read(TEMP_DIR . '/3/x/file'));

	Assert::exception(function () {
		FileSystem::copy(TEMP_DIR . '/5', TEMP_DIR . '/3', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::true(is_dir(TEMP_DIR . '/3/x/y'));
	Assert::false(file_exists(TEMP_DIR . '/3/newfile'));

	FileSystem::copy(TEMP_DIR . '/5', TEMP_DIR . '/3');
	Assert::false(file_exists(TEMP_DIR . '/3/x/y'));
	Assert::true(is_file(TEMP_DIR . '/3/newfile'));
});

Assert::exception(function () {
	FileSystem::copy(TEMP_DIR . '/6', TEMP_DIR . '/3');
}, Nette\IOException::class, "File or directory '%S%' not found.");


test(function () { // delete
	FileSystem::write(TEMP_DIR . '/7/file', 'Hello');
	FileSystem::delete(TEMP_DIR . '/7/file');
	Assert::true(is_dir(TEMP_DIR . '/7'));

	FileSystem::write(TEMP_DIR . '/7/file', 'Hello');
	FileSystem::delete(TEMP_DIR . '/7');
	Assert::false(file_exists(TEMP_DIR . '/7'));
});


test(function () { // move
	FileSystem::write(TEMP_DIR . '/12/file', 'Hello');

	FileSystem::move(TEMP_DIR . '/12/file', TEMP_DIR . '/12/x/file');
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/12/x/file'));
	Assert::false(file_exists(TEMP_DIR . '/12/file'));

	FileSystem::write(TEMP_DIR . '/13/newfile', 'World');

	Assert::exception(function () {
		FileSystem::move(TEMP_DIR . '/13/newfile', TEMP_DIR . '/12/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/12/x/file'));

	FileSystem::move(TEMP_DIR . '/13/newfile', TEMP_DIR . '/12/x/file');
	Assert::false(file_exists(TEMP_DIR . '/13/newfile'));

	Assert::exception(function () {
		FileSystem::move(TEMP_DIR . '/12', TEMP_DIR . '/13', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::true(is_dir(TEMP_DIR . '/13'));

	FileSystem::move(TEMP_DIR . '/12', TEMP_DIR . '/13');
	Assert::false(file_exists(TEMP_DIR . '/12/x'));
	Assert::true(is_file(TEMP_DIR . '/13/x/file'));
	Assert::false(file_exists(TEMP_DIR . '/12/x/file'));
});

Assert::exception(function () {
	FileSystem::move(TEMP_DIR . '/14', TEMP_DIR . '/12');
}, Nette\IOException::class, "File or directory '%S%' not found.");


test(function () { // rename
	FileSystem::write(TEMP_DIR . '/8/file', 'Hello');
	FileSystem::rename(TEMP_DIR . '/8', TEMP_DIR . '/9');
	FileSystem::rename(TEMP_DIR . '/9/file', TEMP_DIR . '/9/x/file');
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/9/x/file'));

	FileSystem::write(TEMP_DIR . '/8/newfile', 'World');
	Assert::exception(function () {
		FileSystem::rename(TEMP_DIR . '/8/newfile', TEMP_DIR . '/9/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(TEMP_DIR . '/9/x/file'));
	FileSystem::rename(TEMP_DIR . '/8/newfile', TEMP_DIR . '/9/x/file');
	Assert::same('World', FileSystem::read(TEMP_DIR . '/9/x/file'));

	FileSystem::createDir(TEMP_DIR . '/10/');
	Assert::exception(function () {
		FileSystem::rename(TEMP_DIR . '/10', TEMP_DIR . '/9', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('World', FileSystem::read(TEMP_DIR . '/9/x/file'));

	FileSystem::rename(TEMP_DIR . '/10', TEMP_DIR . '/9');
	Assert::false(file_exists(TEMP_DIR . '/9/x/file'));
	Assert::false(file_exists(TEMP_DIR . '/10'));

	FileSystem::createDir(TEMP_DIR . '/11/');
	FileSystem::rename(TEMP_DIR . '/11', TEMP_DIR . '/11');
	Assert::true(file_exists(TEMP_DIR . '/11'));
	FileSystem::rename(TEMP_DIR . '/11', TEMP_DIR . '/11/');
	Assert::true(file_exists(TEMP_DIR . '/11'));
});

Assert::exception(function () {
	FileSystem::rename(TEMP_DIR . '/10', TEMP_DIR . '/9');
}, Nette\IOException::class, "File or directory '%S%' not found.");


test(function () { // isAbsolute
	Assert::false(FileSystem::isAbsolute(''));
	Assert::true(FileSystem::isAbsolute('\\'));
	Assert::true(FileSystem::isAbsolute('//'));
	Assert::false(FileSystem::isAbsolute('file'));
	Assert::false(FileSystem::isAbsolute('dir:/file'));
	Assert::false(FileSystem::isAbsolute('dir:\file'));
	Assert::true(FileSystem::isAbsolute('d:/file'));
	Assert::true(FileSystem::isAbsolute('d:\file'));
	Assert::true(FileSystem::isAbsolute('D:\file'));
	Assert::true(FileSystem::isAbsolute('http://file'));
	Assert::true(FileSystem::isAbsolute('remote://file'));
});
