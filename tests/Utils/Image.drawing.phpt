<?php

/**
 * Test: Nette\Image drawing.
 *
 * @author     David Grudl
 * @package    Nette
 */

use Nette\Image;


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
