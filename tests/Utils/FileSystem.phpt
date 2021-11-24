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
	public function stream_read()
	{
		return '';
	}


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


test('createDir', function () {
	FileSystem::createDir(getTempDir() . '/1/b/');
	Assert::true(is_dir(getTempDir() . '/1/b'));

	FileSystem::createDir(getTempDir() . '/1/');
});

Assert::exception(function () {
	FileSystem::createDir('');
}, Nette\IOException::class, "Unable to create directory '' with mode 777.%A%");


test('write + read', function () {
	FileSystem::write(getTempDir() . '/2/file', 'Hello');
	Assert::true(is_file(getTempDir() . '/2/file'));
	Assert::same('Hello', FileSystem::read(getTempDir() . '/2/file'));
});

Assert::exception(function () {
	FileSystem::write('', 'Hello');
}, Nette\IOException::class, "Unable to create directory '' with mode 777.%A%");

Assert::exception(function () {
	FileSystem::read('missing');
}, Nette\IOException::class, "Unable to read file 'missing'. %a%");


test('copy', function () {
	Assert::false(stream_is_local('remote://example.com'));

	FileSystem::write(getTempDir() . '/3/file', 'Hello');

	FileSystem::copy(getTempDir() . '/3/file', getTempDir() . '/3/x/file');
	Assert::same('Hello', FileSystem::read(getTempDir() . '/3/x/file'));

	FileSystem::copy('remote://example.com', getTempDir() . '/3/x/y/file');
	Assert::true(is_file(getTempDir() . '/3/x/y/file'));

	FileSystem::write(getTempDir() . '/5/newfile', 'World');

	Assert::exception(function () {
		FileSystem::copy(getTempDir() . '/5/newfile', getTempDir() . '/3/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(getTempDir() . '/3/x/file'));

	Assert::exception(function () {
		FileSystem::copy('remote://example.com', getTempDir() . '/3/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(getTempDir() . '/3/x/file'));

	FileSystem::copy(getTempDir() . '/5/newfile', getTempDir() . '/3/x/file');
	Assert::same('World', FileSystem::read(getTempDir() . '/3/x/file'));

	Assert::exception(function () {
		FileSystem::copy(getTempDir() . '/5', getTempDir() . '/3', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::true(is_dir(getTempDir() . '/3/x/y'));
	Assert::false(file_exists(getTempDir() . '/3/newfile'));

	FileSystem::copy(getTempDir() . '/5', getTempDir() . '/3');
	Assert::false(file_exists(getTempDir() . '/3/x/y'));
	Assert::true(is_file(getTempDir() . '/3/newfile'));
});

Assert::exception(function () {
	FileSystem::copy(getTempDir() . '/6', getTempDir() . '/3');
}, Nette\IOException::class, "File or directory '%S%' not found.");


test('delete', function () {
	FileSystem::write(getTempDir() . '/7/file', 'Hello');
	FileSystem::delete(getTempDir() . '/7/file');
	Assert::true(is_dir(getTempDir() . '/7'));

	FileSystem::write(getTempDir() . '/7/file', 'Hello');
	FileSystem::delete(getTempDir() . '/7');
	Assert::false(file_exists(getTempDir() . '/7'));
});


test('rename', function () {
	FileSystem::write(getTempDir() . '/8/file', 'Hello');
	FileSystem::rename(getTempDir() . '/8', getTempDir() . '/9');
	FileSystem::rename(getTempDir() . '/9/file', getTempDir() . '/9/x/file');
	Assert::same('Hello', FileSystem::read(getTempDir() . '/9/x/file'));

	FileSystem::write(getTempDir() . '/8/newfile', 'World');
	Assert::exception(function () {
		FileSystem::rename(getTempDir() . '/8/newfile', getTempDir() . '/9/x/file', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('Hello', FileSystem::read(getTempDir() . '/9/x/file'));
	FileSystem::rename(getTempDir() . '/8/newfile', getTempDir() . '/9/x/file');
	Assert::same('World', FileSystem::read(getTempDir() . '/9/x/file'));

	FileSystem::createDir(getTempDir() . '/10/');
	Assert::exception(function () {
		FileSystem::rename(getTempDir() . '/10', getTempDir() . '/9', false);
	}, Nette\InvalidStateException::class, "File or directory '%a%' already exists.");
	Assert::same('World', FileSystem::read(getTempDir() . '/9/x/file'));

	FileSystem::rename(getTempDir() . '/10', getTempDir() . '/9');
	Assert::false(file_exists(getTempDir() . '/9/x/file'));
	Assert::false(file_exists(getTempDir() . '/10'));

	FileSystem::createDir(getTempDir() . '/11/');
	FileSystem::rename(getTempDir() . '/11', getTempDir() . '/11');
	Assert::true(file_exists(getTempDir() . '/11'));
	FileSystem::rename(getTempDir() . '/11', getTempDir() . '/11/');
	Assert::true(file_exists(getTempDir() . '/11'));
});

Assert::exception(function () {
	FileSystem::rename(getTempDir() . '/10', getTempDir() . '/9');
}, Nette\IOException::class, "File or directory '%S%' not found.");


test('isAbsolute', function () {
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


if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
	test('makeWritable', function () {
		FileSystem::createDir(getTempDir() . '/12/x');
		FileSystem::write(getTempDir() . '/12/x/file', 'Hello');
		chmod(getTempDir() . '/12/x/file', 0444);
		chmod(getTempDir() . '/12/x', 0555);
		chmod(getTempDir() . '/12', 0555);

		FileSystem::makeWritable(getTempDir() . '/12');

		Assert::same(0777, fileperms(getTempDir() . '/12') & 0777);
		Assert::same(0777, fileperms(getTempDir() . '/12/x') & 0777);
		Assert::same(0666, fileperms(getTempDir() . '/12/x/file') & 0777);
	});
}

Assert::exception(function () {
	FileSystem::makeWritable(getTempDir() . '/13');
}, Nette\IOException::class, "File or directory '%S%' not found.");
