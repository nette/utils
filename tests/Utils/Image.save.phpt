<?php

/**
 * Test: Nette\Utils\Image save method exceptions.
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$main = Image::fromFile(__DIR__ . '/images/alpha1.png');


test(function () use ($main) {
	$main->save(TEMP_DIR . '/foo.png');
	Assert::true(is_file(TEMP_DIR . '/foo.png'));
	Assert::same(IMAGETYPE_PNG, getimagesize(TEMP_DIR . '/foo.png')[2]);
});


test(function () use ($main) {
	$main->save(TEMP_DIR . '/foo.x', NULL, Image::PNG);
	Assert::true(is_file(TEMP_DIR . '/foo.x'));
	Assert::same(IMAGETYPE_PNG, getimagesize(TEMP_DIR . '/foo.x')[2]);
});


test(function () use ($main) {
	if (!function_exists('imagewebp')) {
		return;
	}

	$main->save(TEMP_DIR . '/foo.webp');
	Assert::true(is_file(TEMP_DIR . '/foo.webp'));
	Assert::same('WEBP', file_get_contents(TEMP_DIR . '/foo.webp', FALSE, NULL, 8, 4));

	$main->save(TEMP_DIR . '/foo.y', NULL, Image::WEBP);
	Assert::true(is_file(TEMP_DIR . '/foo.y'));
	Assert::same('WEBP', file_get_contents(TEMP_DIR . '/foo.y', FALSE, NULL, 8, 4));
});


Assert::exception(function () use ($main) { // invalid image type
	$main->save('foo', NULL, IMG_WBMP);
}, Nette\InvalidArgumentException::class, sprintf('Unsupported image type \'%d\'.', IMG_WBMP));


Assert::exception(function () use ($main) { // invalid file extension
	$main->save('foo.psd');
}, Nette\InvalidArgumentException::class, 'Unsupported file extension \'psd\'.');
