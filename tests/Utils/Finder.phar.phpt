<?php

/**
 * Test: Nette\Utils\Finder browsing PHAR.
 *
 * @phpIni phar.readonly=0
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$pharFile = getTempDir() . '/test.phar';

$phar = new Phar($pharFile);
$phar['a.php'] = '';
$phar['b.php'] = '';
$phar['sub/c.php'] = '';
unset($phar);

Assert::true(is_file($pharFile));
Phar::loadPhar($pharFile, 'test.phar');


test('from()', function () {
	$finder = Finder::findFiles('*')
		->from('phar://test.phar');

	Assert::same([
		'phar://test.phar' . DIRECTORY_SEPARATOR . 'a.php',
		'phar://test.phar' . DIRECTORY_SEPARATOR . 'b.php',
		'phar://test.phar' . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'c.php',
	], array_keys(iterator_to_array($finder)));
});

test('files()', function () {
	$finder = Finder::findFiles('phar://test.phar/*');

	Assert::same([
		'phar://test.phar' . DIRECTORY_SEPARATOR . 'a.php',
		'phar://test.phar' . DIRECTORY_SEPARATOR . 'b.php',
	], array_keys(iterator_to_array($finder)));
});
