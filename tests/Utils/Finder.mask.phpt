<?php

/**
 * Test: Nette\Utils\Finder mask tests.
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

	sort($arr);
	return $arr;
}


test('multiple file masks find matching files in a directory', function () {
	$finder = Finder::findFiles('*.txt', '*.gif')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('array of file masks returns expected matching files', function () {
	$finder = Finder::findFiles(['*.txt', '*.gif'])->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('multiple masks in a subdirectory return files and directories', function () {
	$finder = Finder::findFiles('*.txt', '*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('mask with dot character excludes files without extension', function () {
	$finder = Finder::findFiles('*.*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
	], export($finder));
});


test('excluding subdirectories by pattern filters out unwanted files', function () {
	$finder = Finder::findFiles('*')->exclude('*i*/*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));
});


test('nested wildcard pattern finds files in deeper subdirectories', function () {
	$finder = Finder::findFiles('*/*2/*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('excluding files by pattern in subdirectory returns remaining entries', function () {
	$finder = Finder::findFiles('*')->exclude('*i*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('excluding nested patterns in base directory filters correctly', function () {
	$finder = Finder::findFiles('*')->exclude('*i*/*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));
});


test('complex mask with character classes matches the expected file', function () {
	$finder = Finder::findFiles('*2*/fi??.*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('masks with character classes and escaped brackets match files', function () {
	$finder = Finder::findFiles('*[efd][a-z][!a-r]*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/images/logo.gif',
	], export($finder));

	$finder = Finder::findFiles('[[]x[]]/fil[e].*')->in('fixtures.finder2');
	Assert::same([
		'fixtures.finder2/[x]/file.txt',
	], export($finder));
});


test('bracket patterns match directory names correctly', function () {
	$finder = Finder::findFiles('[x]/fil[e].*')->in('fixtures.finder2');
	Assert::same([
		'fixtures.finder2/x/file.txt',
	], export($finder));

	$finder = Finder::findFiles('[x]/fil[e].*')->from('fixtures.finder2');
	Assert::same([
		'fixtures.finder2/x/file.txt',
	], export($finder));
});


test('wildcard with bracketed directory pattern matches files', function () {
	$finder = Finder::findFiles('*')->in('fixtures.finder*/[x]');
	Assert::same([
		'fixtures.finder2/[x]/file.txt',
	], export($finder));

	$finder = Finder::findFiles('*')->from('fixtures.finder*/[x]');
	Assert::same([
		'fixtures.finder2/[x]/file.txt',
	], export($finder));
});


test('double asterisk wildcard behaves differently in from() and in()', function () {
	$finder = Finder::findFiles('**/f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));

	$finder = Finder::findFiles('**/f*')->in('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));
});


test('relative path masks with "./" prefix work correctly', function () {
	$finder = Finder::findFiles('./f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));

	$finder = Finder::findFiles('./*/f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
	], export($finder));

	$finder = Finder::findFiles('./f*')->in('fixtures.finder');
	Assert::same([
		'fixtures.finder/./file.txt',
	], export($finder));
});


test('parent directory references are handled differently in from() versus in()', function () {
	// not supported
	$finder = Finder::findFiles('../f*')->from('fixtures.finder/subdir');
	Assert::same([], export($finder));

	$finder = Finder::findFiles('../f*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/../file.txt',
	], export($finder));
});


test('combined relative and recursive wildcards match nested files', function () {
	$finder = Finder::findFiles('./**/f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('finder returns directories matching the given pattern', function () {
	$finder = Finder::find('s*/**')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/subdir2',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('glob pattern for directory names returns different file sets', function () {
	$finder = Finder::findFiles('f*')->in('*.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));

	$finder = Finder::findFiles('f*')->from('*.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('recursive glob patterns yield varying results with in() and from()', function () {
	$finder = Finder::findFiles('f*')->in('**/fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));

	$finder = Finder::findFiles('f*')->from('**/fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});
