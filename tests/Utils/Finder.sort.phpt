<?php

/**
 * Test: Nette\Utils\Finder sorting.
 */

declare(strict_types=1);

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


test('basic', function () {
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


test('and', function () {
	$finder = Finder::find('*')->in('fixtures.finder/subdir')
		->and()
		->files('*')->in('fixtures.finder/images')
		->sortByName();

	Assert::same([
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/subdir2',
	], export($finder));
});
