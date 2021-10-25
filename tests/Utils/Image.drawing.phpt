<?php

/**
 * Test: Nette\Utils\Image drawing.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function toGd2($image)
{
	ob_start();
	imagegd2($image->getImageResource());
	return ob_get_clean();
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

$file = defined('PHP_WINDOWS_VERSION_BUILD') && PHP_VERSION_ID >= 70424
	? '/expected/Image.drawing.1b.gd2'
	: '/expected/Image.drawing.1.gd2';
Assert::same(file_get_contents(__DIR__ . $file), toGd2($image));


// palette-based image
$image = Image::fromFile(__DIR__ . '/fixtures.images/logo.gif');
$image->filledEllipse(100, 50, 50, 50, Image::rgb(255, 255, 0, 75));
$image->filledEllipse(100, 150, 50, 50, Image::rgb(255, 255, 0, 75));
Assert::same(file_get_contents(__DIR__ . '/expected/Image.drawing.2.gd2'), toGd2($image));
