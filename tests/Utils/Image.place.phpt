<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Image place image.
 * @phpExtension gd
 */

use Nette\Utils\Image;

require __DIR__ . '/../bootstrap.php';


$rectangle = Image::fromBlank(50, 50, Image::rgb(255, 255, 255));

$image = Image::fromBlank(100, 100, Image::rgb(0, 0, 0));
$image->place($rectangle, '37.5%', '50%');

assertImage(__DIR__ . '/expected/Image.place.png', $image);
