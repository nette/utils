<?php

/**
 * Test: Nette\Utils\Finder filters.
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

	sort($arr);
	return $arr;
}


test('size filter', function () {
	$finder = Finder::findFiles('*')->size('>8kB')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/images/logo.gif',
	], export($finder));
});


test('size filters', function () {
	$finder = Finder::findFiles('*')->size('> 10')->size('< 100b')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('size filters', function () {
	$finder = Finder::find('*')->size('>', 10)->size('< 100b')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/subdir',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/subdir2',
	], export($finder));
});


test('date filter', function () {
	$finder = Finder::findFiles('*')->date('> 2050-01-02')->from('fixtures.finder');
	Assert::same([], export($finder));
});


test('custom filter', function () {
	$finder = Finder::findFiles('*')
		->from('fixtures.finder')
		->filter(fn(FileInfo $file) => $file->getBaseName() === 'file.txt');

	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('custom filter', function () {
	function filter(FileInfo $file)
	{
		return $file->getBaseName() === 'file.txt';
	}


	$finder = Finder::findFiles('*')
		->from('fixtures.finder')
		->filter('filter');

	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});
