<?php

/**
 * Test: Nette\Utils\Image save method exceptions.
 */

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$main = Image::fromFile('images/logo.gif');


Assert::exception(function () use ($main) { // invalid image type
	$main->save('foo', NULL, IMG_WBMP);
}, Nette\InvalidArgumentException::class, sprintf('Unsupported image type \'%d\'.', IMG_WBMP));


Assert::exception(function () use ($main) { // invalid file extension
	$main->save('foo.psd');
}, Nette\InvalidArgumentException::class, 'Unsupported file extension \'psd\'.');
