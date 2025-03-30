<?php

/**
 * Test: Nette\Utils\Image factories.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('loading GIF image and detecting dimensions', function () {
	$image = Image::fromFile(__DIR__ . '/fixtures.images/logo.gif', $format);
	Assert::same(176, $image->getWidth());
	Assert::same(104, $image->getHeight());
	Assert::same(Image::GIF, $format);

	Assert::same(Image::GIF, Image::detectTypeFromFile(__DIR__ . '/fixtures.images/logo.gif', $w, $h));
	Assert::same(176, $w);
	Assert::same(104, $h);
});


test('loading WEBP image if supported', function () {
	if (!Image::isTypeSupported(Image::WEBP)) {
		return;
	}

	$image = Image::fromFile(__DIR__ . '/fixtures.images/logo.webp', $format);
	Assert::same(176, $image->getWidth());
	Assert::same(104, $image->getHeight());
	Assert::same(Image::WEBP, $format);
});


test('loading AVIF image if supported', function () {
	if (!Image::isTypeSupported(Image::AVIF)) {
		return;
	}

	$image = Image::fromFile(__DIR__ . '/fixtures.images/logo.avif', $format);
	Assert::same(176, $image->getWidth());
	Assert::same(104, $image->getHeight());
	Assert::same(Image::AVIF, $format);
});


Assert::exception(
	fn() => Image::fromFile('fixtures.images/missing.png'),
	Nette\Utils\UnknownImageFileException::class,
	"File 'fixtures.images/missing.png' not found.",
);


Assert::null(Image::detectTypeFromFile('fixtures.images/missing.png'));


Assert::exception(
	fn() => Image::fromFile(__DIR__ . '/fixtures.images/logo.tiff'),
	Nette\Utils\UnknownImageFileException::class,
	"Unknown type of file '%a%fixtures.images/logo.tiff'.",
);


Assert::exception(
	fn() => Image::fromFile(__DIR__ . '/fixtures.images/bad.gif'),
	Nette\Utils\ImageException::class,
	'%a% not a valid GIF file',
);


test('creating blank image with background color', function () {
	$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
	Assert::same(200, $image->getWidth());
	Assert::same(300, $image->getHeight());
});


test('creating image from empty GIF string and detecting type', function () {
	$image = Image::fromString(Image::EmptyGIF, $format);
	Assert::same(1, $image->getWidth());
	Assert::same(1, $image->getHeight());
	Assert::same(Image::GIF, $format);

	Assert::same(Image::GIF, Image::detectTypeFromString(Image::EmptyGIF, $w, $h));
	Assert::same(1, $w);
	Assert::same(1, $h);
});


Assert::exception(
	fn() => Image::fromString('abcdefg'),
	Nette\Utils\UnknownImageFileException::class,
);


Assert::null(Image::detectTypeFromString('x'));


Assert::same('webp', Image::typeToExtension(Image::WEBP));
Assert::same('jpeg', Image::typeToExtension(Image::JPEG));
Assert::same('image/webp', Image::typeToMimeType(Image::WEBP));
Assert::same('image/jpeg', Image::typeToMimeType(Image::JPEG));
Assert::same(Image::WEBP, Image::extensionToType('webp'));
Assert::same(Image::JPEG, Image::extensionToType('jpeg'));
Assert::same(Image::JPEG, Image::extensionToType('jpg'));
