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


test('multiple mask', function () {
	$finder = Finder::findFiles('*.txt', '*.gif')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('', function () {
	$finder = Finder::findFiles(['*.txt', '*.gif'])->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('* mask', function () {
	$finder = Finder::findFiles('*.txt', '*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('*.* mask', function () {
	$finder = Finder::findFiles('*.*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
	], export($finder));
});


// subdir excluding mask
$finder = Finder::findFiles('*')->exclude('*i*/*')->from('fixtures.finder');
Assert::same([
	'fixtures.finder/file.txt',
], export($finder));


test('subdir mask', function () {
	$finder = Finder::findFiles('*/*2/*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('excluding mask', function () {
	$finder = Finder::findFiles('*')->exclude('*i*')->in('fixtures.finder/subdir');
	Assert::same([
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('subdir excluding mask', function () {
	$finder = Finder::findFiles('*')->exclude('*i*/*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));
});


test('complex mask', function () {
	$finder = Finder::findFiles('*[efd][a-z][!a-r]*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/images/logo.gif',
	], export($finder));
});


test('', function () {
	$finder = Finder::findFiles('*2*/fi??.*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('anchored', function () {
	$finder = Finder::findFiles('/f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));
});


test('', function () {
	$finder = Finder::findFiles('/*/f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
	], export($finder));
});


test('multidirs mask', function () {
	$finder = Finder::findFiles('/**/f*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});
