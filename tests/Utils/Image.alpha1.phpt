<?php

/**
 * Test: Nette\Utils\Image alpha channel.
 */

use Nette\Utils\Image,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


ob_start();

$image = Image::fromBlank(200, 100, Image::rgb(255, 128, 0, 60));
$image->crop(0, 0, '60%', '60%');
$image->send(Image::PNG, 0);

Assert::same(file_get_contents(__DIR__ . '/Image.alpha1.expect'), ob_get_clean());
