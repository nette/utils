<?php declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$sep = DIRECTORY_SEPARATOR;
	Assert::same('', FileSystem::joinPaths(''));
	Assert::same($sep, FileSystem::joinPaths('\\'));
	Assert::same($sep, FileSystem::joinPaths('/'));
	Assert::same("a{$sep}b", FileSystem::joinPaths('a', 'b'));
	Assert::same("{$sep}a{$sep}b{$sep}", FileSystem::joinPaths('/a/', '/b/'));
	Assert::same("{$sep}", FileSystem::joinPaths('/a/', '/../'));
	Assert::same("phar://a{$sep}b", FileSystem::joinPaths('phar://a', 'b'));
});
