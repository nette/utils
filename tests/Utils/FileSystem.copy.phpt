<?php

/**
 * Test: Nette\Utils\FileSystem copy()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class RemoteStream /* extends \streamWrapper */
{
	public $context;


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


test('copy', function () {
	Assert::false(stream_is_local('remote://example.com'));

	FileSystem::write(getTempDir() . '/3/file', 'Hello');

	FileSystem::copy(getTempDir() . '/3/file', getTempDir() . '/3/x/file');
	Assert::same('Hello', FileSystem::read(getTempDir() . '/3/x/file'));

	FileSystem::copy('remote://example.com', getTempDir() . '/3/x/y/file');
	Assert::true(is_file(getTempDir() . '/3/x/y/file'));

	FileSystem::write(getTempDir() . '/5/newfile', 'World');

	Assert::exception(
		fn() => FileSystem::copy(getTempDir() . '/5/newfile', getTempDir() . '/3/x/file', false),
		Nette\InvalidStateException::class,
		"File or directory '%a%' already exists.",
	);
	Assert::same('Hello', FileSystem::read(getTempDir() . '/3/x/file'));

	Assert::exception(
		fn() => FileSystem::copy('remote://example.com', getTempDir() . '/3/x/file', false),
		Nette\InvalidStateException::class,
		"File or directory '%a%' already exists.",
	);
	Assert::same('Hello', FileSystem::read(getTempDir() . '/3/x/file'));

	FileSystem::copy(getTempDir() . '/5/newfile', getTempDir() . '/3/x/file');
	Assert::same('World', FileSystem::read(getTempDir() . '/3/x/file'));

	Assert::exception(
		fn() => FileSystem::copy(getTempDir() . '/5', getTempDir() . '/3', false),
		Nette\InvalidStateException::class,
		"File or directory '%a%' already exists.",
	);
	Assert::true(is_dir(getTempDir() . '/3/x/y'));
	Assert::false(file_exists(getTempDir() . '/3/newfile'));

	FileSystem::copy(getTempDir() . '/5', getTempDir() . '/3');
	Assert::false(file_exists(getTempDir() . '/3/x/y'));
	Assert::true(is_file(getTempDir() . '/3/newfile'));
});

Assert::exception(
	fn() => FileSystem::copy(getTempDir() . '/6', getTempDir() . '/3'),
	Nette\IOException::class,
	"File or directory '%S%' not found.",
);
