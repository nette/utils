<?php

/**
 * Test: Nette\Utils\Image crop, resize & flip.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$main = Image::fromFile(__DIR__ . '/fixtures.images/logo.gif');


function toGd2($image)
{
	ob_start();
	imagegd2($image->getImageResource());
	return ob_get_clean();
}


test('cropping...', function () use ($main) {
	$image = clone $main;
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);

	$image->crop(10, 20, 50, 300);
	Assert::same(50, $image->width);
	Assert::same(84, $image->height);
});


test('resizing X', function () use ($main) {
	$image = clone $main;
	$image->resize(150, null);
	Assert::same(150, $image->width);
	Assert::same(89, $image->height);
});


test('resizing Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(null, 150, Image::SHRINK_ONLY);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('resizing Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(null, 150, shrinkOnly: true);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('resizing X Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 150, Image::SHRINK_ONLY);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('resizing X Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 150, shrinkOnly: true);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('resizing X Y', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 150);
	Assert::same(254, $image->width);
	Assert::same(150, $image->height);
});


test('resizing X Y stretch', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 100, Image::STRETCH);
	Assert::same(300, $image->width);
	Assert::same(100, $image->height);
});


test('resizing X Y shrink stretch', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 100, Image::SHRINK_ONLY | Image::STRETCH);
	Assert::same(176, $image->width);
	Assert::same(100, $image->height);
});


test('resizing X Y shrink stretch', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 100, Image::STRETCH, shrinkOnly: true);
	Assert::same(176, $image->width);
	Assert::same(100, $image->height);
});


test('resizing X%', function () use ($main) {
	$image = clone $main;
	$image->resize('110%', null);
	Assert::same(194, $image->width);
	Assert::same(115, $image->height);
});


test('resizing X% Y%', function () use ($main) {
	$image = clone $main;
	$image->resize('110%', '90%');
	Assert::same(194, $image->width);
	Assert::same(94, $image->height);
});


test('flipping X', function () use ($main) {
	$image = clone $main;
	$image->resize(-150, null);
	Assert::same(150, $image->width);
	Assert::same(89, $image->height);
});


test('flipping Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(null, -150, Image::SHRINK_ONLY);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('flipping Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(null, -150, shrinkOnly: true);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('flipping X Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(-300, -150, Image::SHRINK_ONLY);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('flipping X Y shrink', function () use ($main) {
	$image = clone $main;
	$image->resize(-300, -150, shrinkOnly: true);
	Assert::same(176, $image->width);
	Assert::same(104, $image->height);
});


test('exact resize', function () use ($main) {
	$image = clone $main;
	$image->resize(300, 150, Image::EXACT);
	Assert::same(300, $image->width);
	Assert::same(150, $image->height);
});


test('rotate', function () use ($main) {
	$image = clone $main;
	$image->rotate(90, Image::rgb(0, 0, 0));
	Assert::same(104, $image->width);
	Assert::same(176, $image->height);
});


test('alpha crop', function () use ($main) {
	$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
	$image->crop(1, 1, 8, 8);
	Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha.crop.gd2'), toGd2($image));
	if (PHP_VERSION_ID < 70200 && gd_info()['GD Version'] === 'bundled (2.1.0 compatible)') {
		Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha.crop-71.png'), $image->toString($image::PNG));
	} else {
		Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha.crop.png'), $image->toString($image::PNG));
	}
});


test('alpha resize', function () use ($main) {
	$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
	$image->resize(20, 20);
	Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha.resize1.gd2'), toGd2($image));
});


test('alpha flip', function () use ($main) {
	$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');
	$image->resize(-10, -10);
	Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha.flip1.gd2'), toGd2($image));
});


test('palette alpha resize', function () use ($main) {
	$image = Image::fromFile(__DIR__ . '/fixtures.images/alpha3.gif');
	$image->resize(20, 20);
	Assert::same(file_get_contents(__DIR__ . '/expected/Image.alpha.resize2.gd2'), toGd2($image));
});
