<?php

/**
 * Test: Nette\Utils\Image drawing.
 */

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$size = 300;
$image = Image::fromBlank($size, $size);

$image->filledRectangle(0, 0, $size - 1, $size - 1, Image::rgb(255, 255, 255));
$image->rectangle(0, 0, $size - 1, $size - 1, Image::rgb(0, 0, 0));

$radius = 150;

$image->filledEllipse(100, 75, $radius, $radius, Image::rgb(255, 255, 0, 75));
$image->filledEllipse(120, 168, $radius, $radius, Image::rgb(255, 0, 0, 75));
$image->filledEllipse(187, 125, $radius, $radius, Image::rgb(0, 0, 255, 75));

$image->copyResampled($image, 200, 200, 0, 0, 80, 80, $size, $size);

Assert::same(file_get_contents(__DIR__ . '/Image.drawing.expect'), $image->toString(Image::PNG, 0));


// palette-based image
$image = Image::fromFile(__DIR__.'/images/logo.gif');
$image->filledEllipse(100, 50, 50, 50, Image::rgb(255, 255, 0, 75));
$image->filledEllipse(100, 150, 50, 50, Image::rgb(255, 255, 0, 75));
Assert::same(file_get_contents(__DIR__ . '/Image.drawing2.expect'), $image->toString(Image::PNG, 0));
