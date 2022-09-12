<?php

/**
 * Test: Nette\Utils\Image place image.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$rectangle = Image::fromBlank(50, 50, Image::rgb(255, 255, 255));

$image = Image::fromBlank(100, 100, Image::rgb(0, 0, 0));
$image->place($rectangle, '37.5%', '50%');

Assert::same(file_get_contents(__DIR__ . '/expected/Image.place.png'), $image->toString($image::PNG));
