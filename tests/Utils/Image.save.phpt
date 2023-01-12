<?php

/**
 * Test: Nette\Utils\Image save method exceptions.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$main = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');


test('', function () use ($main) {
	$main->save(getTempDir() . '/foo.png');
	Assert::true(is_file(getTempDir() . '/foo.png'));
	Assert::same(IMAGETYPE_PNG, getimagesize(getTempDir() . '/foo.png')[2]);
});


test('', function () use ($main) {
	$main->save(getTempDir() . '/foo.x', null, Image::PNG);
	Assert::true(is_file(getTempDir() . '/foo.x'));
	Assert::same(IMAGETYPE_PNG, getimagesize(getTempDir() . '/foo.x')[2]);
});


test('', function () use ($main) {
	if (!function_exists('imagewebp')) {
		return;
	}

	$main->save(getTempDir() . '/foo.webp');
	Assert::true(is_file(getTempDir() . '/foo.webp'));
	Assert::same('WEBP', file_get_contents(getTempDir() . '/foo.webp', false, null, 8, 4));

	$main->save(getTempDir() . '/foo.y', null, Image::WEBP);
	Assert::true(is_file(getTempDir() . '/foo.y'));
	Assert::same('WEBP', file_get_contents(getTempDir() . '/foo.y', false, null, 8, 4));
});


test('', function () use ($main) {
	if (!function_exists('imageavif')) {
		return;
	}

	$main->save(getTempDir() . '/foo.avif');
	Assert::true(is_file(getTempDir() . '/foo.avif'));
	Assert::same('avif', file_get_contents(getTempDir() . '/foo.avif', false, null, 8, 4));

	$main->save(getTempDir() . '/foo.y', null, Image::AVIF);
	Assert::true(is_file(getTempDir() . '/foo.y'));
	Assert::same('avif', file_get_contents(getTempDir() . '/foo.y', false, null, 8, 4));
});


test('', function () use ($main) {
	if (!function_exists('imagebmp')) {
		return;
	}

	$main->save(getTempDir() . '/foo.bmp');
	Assert::true(is_file(getTempDir() . '/foo.bmp'));
	Assert::same(IMAGETYPE_BMP, getimagesize(getTempDir() . '/foo.bmp')[2]);

	$main->save(getTempDir() . '/foo.y', null, Image::BMP);
	Assert::true(is_file(getTempDir() . '/foo.y'));
	Assert::same(IMAGETYPE_BMP, getimagesize(getTempDir() . '/foo.y')[2]);
});


Assert::exception(
	fn() => $main->save('foo', null, IMG_WBMP),
	Nette\InvalidArgumentException::class,
	sprintf('Unsupported image type \'%d\'.', IMG_WBMP),
);


Assert::exception(
	fn() => $main->save('foo.psd'),
	Nette\InvalidArgumentException::class,
	'Unsupported file extension \'psd\'.',
);
