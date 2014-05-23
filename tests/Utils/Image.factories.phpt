<?php

/**
 * Test: Nette\Utils\Image factories.
 */

use Nette\Utils\Image,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


test(function() {
	$image = Image::fromFile('images/logo.gif', $format);
	Assert::same( 176, $image->getWidth() );
	Assert::same( 104, $image->getHeight() );
	Assert::same( Image::GIF, $format );
});


Assert::exception(function() {
	Image::fromFile('images/missing.png');
}, 'Nette\Utils\UnknownImageFileException', "Unknown image type or file 'images/missing.png' not found.");


Assert::exception(function() {
	Image::fromFile('images/logo.tiff');
}, 'Nette\Utils\UnknownImageFileException', "Unknown image type or file 'images/logo.tiff' not found.");


Assert::exception(function() {
	Image::fromFile('images/bad.gif');
}, 'Nette\Utils\ImageException', '%a% not a valid GIF file');


test(function() {
	$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
	Assert::same( 200, $image->getWidth() );
	Assert::same( 300, $image->getHeight() );
});


test(function() {
	$image = Image::fromString(Image::EMPTY_GIF, $format);
	Assert::same( 1, $image->getWidth() );
	Assert::same( 1, $image->getHeight() );
	Assert::same( Image::GIF, $format );
});


Assert::exception(function () {
	Image::fromString('abcdefg');
}, 'Nette\Utils\ImageException');
