<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$S = DIRECTORY_SEPARATOR;
	Assert::same('', FileSystem::joinPaths(''));
	Assert::same($S, FileSystem::joinPaths('\\'));
	Assert::same($S, FileSystem::joinPaths('/'));
	Assert::same("a{$S}b", FileSystem::joinPaths('a', 'b'));
	Assert::same("{$S}a{$S}b{$S}", FileSystem::joinPaths('/a/', '/b/'));
	Assert::same("{$S}", FileSystem::joinPaths('/a/', '/../'));
});
