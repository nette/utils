<?php

/**
 * Test: Nette\Image crop, resize & flip.
 *
 * @author     David Grudl
 * @package    Nette
 */

use Nette\Image;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$main = Image::fromFile('images/logo.gif');


test(function() use ($main) { // cropping...
	$image = clone $main;
	Assert::same( 176, $image->width );
	Assert::same( 104, $image->height );

	$image->crop(10, 20, 50, 300);
	Assert::same( 50, $image->width );
	Assert::same( 84, $image->height );
});


test(function() use ($main) { // resizing X
	$image = clone $main;
	$image->resize(150, NULL);
	Assert::same( 150, $image->width );
	Assert::same( 89, $image->height );
});


test(function() use ($main) { // resizing Y shrink
	$image = clone $main;
	$image->resize(NULL, 150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );
	Assert::same( 104, $image->height );
});


test(function() use ($main) { // resizing X Y shrink
	$image = clone $main;
	$image->resize(300, 150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );
	Assert::same( 104, $image->height );
});


test(function() use ($main) { // resizing X Y
	$image = clone $main;
	$image->resize(300, 150);
	Assert::same( 254, $image->width );
	Assert::same( 150, $image->height );
});


test(function() use ($main) { // resizing X Y stretch
	$image = clone $main;
	$image->resize(300, 100, Image::STRETCH);
	Assert::same( 300, $image->width );
	Assert::same( 100, $image->height );
});


test(function() use ($main) { // resizing X Y shrink stretch
	$image = clone $main;
	$image->resize(300, 100, Image::SHRINK_ONLY | Image::STRETCH);
	Assert::same( 176, $image->width );
	Assert::same( 100, $image->height );
});


test(function() use ($main) { // resizing X%
	$image = clone $main;
	$image->resize('110%', NULL);
	Assert::same( 194, $image->width );
	Assert::same( 115, $image->height );
});


test(function() use ($main) { // resizing X% Y%
	$image = clone $main;
	$image->resize('110%', '90%');
	Assert::same( 194, $image->width );
	Assert::same( 94, $image->height );
});


test(function() use ($main) { // flipping X
	$image = clone $main;
	$image->resize(-150, NULL);
	Assert::same( 150, $image->width );
	Assert::same( 89, $image->height );
});


test(function() use ($main) { // flipping Y shrink
	$image = clone $main;
	$image->resize(NULL, -150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );
	Assert::same( 104, $image->height );
});


test(function() use ($main) { // flipping X Y shrink
	$image = clone $main;
	$image->resize(-300, -150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );
	Assert::same( 104, $image->height );
});


test(function() use ($main) { // exact resize
	$image = clone $main;
	$image->resize(300, 150, Image::EXACT);
	Assert::same( 300, $image->width );
	Assert::same( 150, $image->height );
});


test(function() use ($main) { // rotate
	$image = clone $main;
	$image->rotate(90, Image::rgb(0, 0, 0));
	Assert::same( 104, $image->width );
	Assert::same( 176, $image->height );
});
