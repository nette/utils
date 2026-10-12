<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Image alpha channel.
 * @phpExtension gd
 */

use Nette\Utils\Image;

require __DIR__ . '/../bootstrap.php';


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'));
assertImage(__DIR__ . '/expected/Image.alpha2.100.png', $image);


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 99);
assertImage(__DIR__ . '/expected/Image.alpha2.99.png', $image);


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 50);
assertImage(__DIR__ . '/expected/Image.alpha2.50.png', $image);


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 1);
assertImage(__DIR__ . '/expected/Image.alpha2.1.png', $image);


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 0);
assertImage(__DIR__ . '/expected/Image.alpha2.0.png', $image);


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif'));
assertImage(__DIR__ . '/expected/Image.alpha2.100b.png', $image);


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif'), 0, 0, 50);
assertImage(__DIR__ . '/expected/Image.alpha2.50b.png', $image);
