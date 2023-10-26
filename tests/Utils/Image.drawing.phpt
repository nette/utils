<?php

/**
 * Test: Nette\Utils\Image drawing.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Nette\Utils\ImageColor;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$size = 300;
$image = Image::fromBlank($size, $size);

$image->filledRectangleWH(0, 0, 300, 300, ImageColor::rgb(255, 255, 255));
$image->rectangleWH(0, 0, 300, 300, ImageColor::rgb(0, 0, 0));

$image->filledRectangleWH(20, 20, -5, -5, ImageColor::rgb(100, 0, 0));
$image->rectangleWH(20, 20, -5, -5, ImageColor::rgb(100, 255, 255));

$image->filledRectangleWH(30, 30, 0, 0, ImageColor::rgb(127, 127, 0));
$image->rectangleWH(35, 35, 0, 0, ImageColor::rgb(127, 127, 0));

$radius = 150;

$image->filledEllipse(100, 75, $radius, $radius, Image::rgb(255, 255, 0, 75));
$image->filledEllipse(120, 168, $radius, $radius, Image::rgb(255, 0, 0, 75));
$image->filledEllipse(187, 125, $radius, $radius, Image::rgb(0, 0, 255, 75));

$image->copyResampled($image, 200, 200, 0, 0, 80, 80, $size, $size);

Assert::same(file_get_contents(__DIR__ . '/expected/Image.drawing.1.png'), $image->toString($image::PNG));


// palette-based image
$image = Image::fromFile(__DIR__ . '/fixtures.images/logo.gif');
$image->filledEllipse(100, 50, 50, 50, Image::rgb(255, 255, 0, 75));
$image->filledEllipse(100, 150, 50, 50, Image::rgb(255, 255, 0, 75));
Assert::same(file_get_contents(__DIR__ . '/expected/Image.drawing.2.png'), $image->toString($image::PNG));
