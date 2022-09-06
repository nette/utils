<?php

/**
 * Test: Nette\Utils\Image alpha channel.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$rectangle = Image::fromBlank(100, 100, Image::rgb(255, 255, 255, 127));
$rectangle->filledRectangle(25, 25, 74, 74, Image::rgb(255, 0, 0, 63));

$image = Image::fromBlank(200, 100, Image::rgb(0, 255, 0));
$image->place($rectangle, 50, 0, 63);

$image2 = Image::fromBlank(200, 100, Image::rgb(0, 255, 0));
$image2->place(Image::fromBlank(50, 50, Image::rgb(80, 174, 0)), 75, 25);

Assert::same($image2->toString(Image::PNG, 0), $image->toString(Image::PNG, 0));

Assert::same($image->toString(), (string) $image);

$image = Image::fromBlank(200, 100, Image::rgb(255, 128, 0, 60));
$image->crop(0, 0, '60%', '60%');

Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha1.png'), $image->toString($image::PNG));
