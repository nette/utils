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
	$image = Image::fromFile('images/logo.gif');
	// logo.gif
	Assert::same( 176, $image->width );

	Assert::same( 104, $image->height );
});


Assert::exception(function () {
	Image::fromFile('images/logo.png');
}, 'Nette\Utils\UnknownImageFileException');


Assert::exception(function () {
	Image::fromFile('images/logo.tiff');
}, 'Nette\Utils\UnknownImageFileException');


test(function() {
	$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
	// blank
	Assert::same( 200, $image->width );

	Assert::same( 300, $image->height );
});


Assert::exception(function () {
	Image::fromString('abcdefg');
}, 'Nette\Utils\ImageException');
