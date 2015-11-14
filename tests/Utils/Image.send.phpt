<?php

/**
 * Test: Nette\Utils\Image send method exceptions.
 */

declare(strict_types = 1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$main = Image::fromFile('images/logo.gif');


Assert::exception(function () use ($main) { // invalid image type
	$main->send(IMG_WBMP);
}, Nette\InvalidArgumentException::class, sprintf('Unsupported image type \'%d\'.', IMG_WBMP));
