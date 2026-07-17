<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Finder exclude() mask grammar.
 */

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


test('unanchored mask matches at any depth', function () {
	$finder = Finder::findFiles('*')->from('fixtures.finder')->exclude('file.txt');
	Assert::same([
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('./ prefix anchors the mask to the search root', function () {
	$finder = Finder::findFiles('*')->from('fixtures.finder')->exclude('./file.txt');
	Assert::same([
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/readme',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('leading **/ is equivalent to the unanchored form', function () {
	$finder = Finder::find('*')->from('fixtures.finder')->exclude('**/subdir/*');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir',
	], export($finder));
});


test('inner ** matches any directory depth', function () {
	$finder = Finder::findFiles('*')->from('fixtures.finder')->exclude('subdir/**/file.txt');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('trailing /** excludes contents but keeps the directory', function () {
	$finder = Finder::find('*')->from('fixtures.finder')->exclude('subdir/**');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir',
	], export($finder));
});


test('globstar short form works in exclude()', function () {
	$finder = Finder::findFiles('*')->from('fixtures.finder')->exclude('**.txt');
	Assert::same([
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir/readme',
	], export($finder));
});


test('absolute and ../ masks are deprecated', function () {
	Assert::error(
		fn() => (new Finder)->exclude('/temp'),
		E_USER_DEPRECATED,
		"Absolute or ../ mask '/temp' in exclude() is deprecated %a%",
	);

	Assert::error(
		fn() => (new Finder)->exclude('../temp'),
		E_USER_DEPRECATED,
		"Absolute or ../ mask '../temp' in exclude() is deprecated %a%",
	);

	Assert::error(
		fn() => (new Finder)->exclude(__DIR__ . '/temp'),
		E_USER_DEPRECATED,
		"Absolute or ../ mask '%a%temp' in exclude() is deprecated %a%",
	);
});


test('deprecated leading slash is still ignored', function () {
	$finder = Finder::find('*')->from('fixtures.finder');
	@$finder->exclude('/subdir/'); // deprecated behavior kept for now
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/images/logo.gif',
	], export($finder));
});


test('invalid mask throws', function () {
	Assert::exception(
		fn() => (new Finder)->exclude(''),
		Nette\InvalidArgumentException::class,
		"Invalid mask ''",
	);
});
