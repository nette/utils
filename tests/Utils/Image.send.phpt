<?php

/**
 * Test: Nette\Utils\Image send method exceptions.
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
	ob_start();
	$main->send();
	$data = ob_get_clean();

	Assert::contains('JFIF', $data);
	if (PHP_SAPI !== 'cli') {
		Assert::contains('Content-Type: image/jpeg', headers_list());
	}
});


test(function () use ($main) {
	ob_start();
	$main->send(Image::PNG);
	$data = ob_get_clean();

	Assert::contains('PNG', $data);
	if (PHP_SAPI !== 'cli') {
		Assert::contains('Content-Type: image/png', headers_list());
	}
});


test(function () use ($main) {
	if (!function_exists('imagewebp')) {
		return;
	}

	ob_start();
	$main->send(Image::WEBP);
	$data = ob_get_clean();

	Assert::contains('WEBP', $data);
	if (PHP_SAPI !== 'cli') {
		Assert::contains('Content-Type: image/webp', headers_list());
	}
});


Assert::exception(function () use ($main) { // invalid image type
	$main->send(IMG_WBMP);
}, Nette\InvalidArgumentException::class, sprintf('Unsupported image type \'%d\'.', IMG_WBMP));
