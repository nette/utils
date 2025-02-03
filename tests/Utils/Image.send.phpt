<?php

/**
 * Test: Nette\Utils\Image send method exceptions.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$main = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');


test('sending image as JPEG by default', function () use ($main) {
	ob_start();
	$main->send();
	$data = ob_get_clean();

	Assert::contains('JFIF', $data);
	if (PHP_SAPI !== 'cli') {
		Assert::contains('Content-Type: image/jpeg', headers_list());
	}
});


test('sending image as PNG', function () use ($main) {
	ob_start();
	$main->send(Image::PNG);
	$data = ob_get_clean();

	Assert::contains('PNG', $data);
	if (PHP_SAPI !== 'cli') {
		Assert::contains('Content-Type: image/png', headers_list());
	}
});


test('sending WEBP image if supported', function () use ($main) {
	if (!Image::isTypeSupported(Image::WEBP)) {
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


Assert::exception(
	fn() => $main->send(IMG_WBMP),
	Nette\InvalidArgumentException::class,
	sprintf('Unsupported image type \'%d\'.', IMG_WBMP),
);
