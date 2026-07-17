<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Finder append.
 */

use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('append finder', function () {
	($finder = new Finder)
		->files('file.txt')
		->in('fixtures.finder')
		->append()
		->directories('subdir*')
		->from('fixtures.finder')
		->append()
		->files('file.txt')
		->from('fixtures.finder/*/subdir*');

	$ds = DIRECTORY_SEPARATOR;
	Assert::same([
		"fixtures.finder{$ds}file.txt",
		"fixtures.finder{$ds}subdir",
		"fixtures.finder{$ds}subdir{$ds}subdir2",
		"fixtures.finder/subdir/subdir2{$ds}file.txt", // the base comes from glob() and keeps the slashes used in from()
	], array_map('strval', $finder->collect()));
});

test('append files', function () {
	($finder = new Finder)
		->append(__FILE__)
		->append(FileSystem::unixSlashes(__DIR__));

	Assert::same([
		__FILE__,
		FileSystem::unixSlashes(__DIR__), // paths are kept verbatim
	], array_map('strval', $finder->collect()));
});
