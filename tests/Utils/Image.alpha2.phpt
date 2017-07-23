<?php

/**
 * Test: Nette\Utils\Image alpha channel.
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


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 100);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.100.gd2'), toGd2($image));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 99);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.99.gd2'), toGd2($image));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 50);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.50.gd2'), toGd2($image));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 1);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.1.gd2'), toGd2($image));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 0);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.0.gd2'), toGd2($image));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif'), 0, 0, 100);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.100b.gd2'), toGd2($image));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif'), 0, 0, 50);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.50b.gd2'), toGd2($image));
