<?php

/**
 * Test: Nette\Utils\Image alpha channel.
 */

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha2.png'), 0, 0, 100);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.100.png'), $image->toString(Image::PNG, 0));


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha2.png'), 0, 0, 99);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.99.png'), $image->toString(Image::PNG, 0));


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha2.png'), 0, 0, 50);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.50.png'), $image->toString(Image::PNG, 0));


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha2.png'), 0, 0, 1);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.1.png'), $image->toString(Image::PNG, 0));


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha2.png'), 0, 0, 0);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.0.png'), $image->toString(Image::PNG, 0));


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha3.gif'), 0, 0, 100);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.100b.png'), $image->toString(Image::PNG, 0));


$image = Image::fromFile(__DIR__ . '/images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/images/alpha3.gif'), 0, 0, 50);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.50b.png'), $image->toString(Image::PNG, 0));
