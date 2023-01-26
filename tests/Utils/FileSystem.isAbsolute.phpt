<?php

/**
 * Test: Nette\Utils\FileSystem isAbsolute()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class RemoteStream /* extends \streamWrapper */
{
}

stream_wrapper_register('remote', RemoteStream::class, STREAM_IS_URL);


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
