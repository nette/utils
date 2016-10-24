<?php

/**
 * Test: Nette\Utils\Image place image.
 */

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$rectangle = Image::fromBlank(50, 50, Image::rgb(255, 255, 255));

$image = Image::fromBlank(100, 100, Image::rgb(0, 0, 0));
$image->place($rectangle, '37.5%', '50%');

ob_start();

$image->send(Image::PNG, 0);

Assert::same(file_get_contents(__DIR__ . '/expected/Image.place.png'), ob_get_clean());
