<?php

/**
 * Test: Nette\Utils\Finder sorting.
 */

declare(strict_types=1);

use Nette\Utils\FileInfo;
use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function export($iterator)
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = strtr($key, '\\', '/');
	}

	return $arr;
}


test('byName', function () {
	$finder = Finder::find('*')
		->from('fixtures.finder')
		->sortByName();

	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/subdir2',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));

	$finder->childFirst();
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/images',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/subdir2/file.txt',
		'fixtures.finder/subdir/subdir2',
		'fixtures.finder/subdir',
	], export($finder));
});

test('user func', function () {
	$finder = Finder::findFiles('*')
		->from('fixtures.finder')
		->sortBy(fn(FileInfo $a, FileInfo $b) => substr((string) $a, -1) <=> substr((string) $b, -1));

	Assert::same([
		'fixtures.finder/subdir/subdir2/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/file.txt',
	], export($finder));
});
