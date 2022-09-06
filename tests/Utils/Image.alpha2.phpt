<?php

/**
 * Test: Nette\Utils\Image alpha channel.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 100);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.100.png'), $image->toString($image::PNG));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 99);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.99.png'), $image->toString($image::PNG));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 50);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.50.png'), $image->toString($image::PNG));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 1);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.1.png'), $image->toString($image::PNG));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha2.png'), 0, 0, 0);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.0.png'), $image->toString($image::PNG));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif'), 0, 0, 100);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.100b.png'), $image->toString($image::PNG));


$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
$image->place(Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif'), 0, 0, 50);
Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha2.50b.png'), $image->toString($image::PNG));
