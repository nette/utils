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
	Tester\Helpers::skip('Requires PHP extension GD.');
}



$main = Image::fromFile('images/logo.gif');


test(function() use ($main) {
	$image = clone $main;
	Assert::same( 176, $image->width );

	Assert::same( 104, $image->height );

	// cropping...
	$image->crop(10, 20, 50, 300);
	Assert::same( 50, $image->width );

	Assert::same( 84, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X
	$image->resize(150, NULL);
	Assert::same( 150, $image->width );

	Assert::same( 89, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing Y shrink
	$image->resize(NULL, 150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );

	Assert::same( 104, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X Y shrink
	$image->resize(300, 150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );

	Assert::same( 104, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X Y
	$image->resize(300, 150);
	Assert::same( 254, $image->width );

	Assert::same( 150, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X Y stretch
	$image->resize(300, 100, Image::STRETCH);
	Assert::same( 300, $image->width );

	Assert::same( 100, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X Y shrink stretch
	$image->resize(300, 100, Image::SHRINK_ONLY | Image::STRETCH);
	Assert::same( 176, $image->width );

	Assert::same( 100, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X%
	$image->resize('110%', NULL);
	Assert::same( 194, $image->width );

	Assert::same( 115, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// resizing X% Y%
	$image->resize('110%', '90%');
	Assert::same( 194, $image->width );

	Assert::same( 94, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// flipping X
	$image->resize(-150, NULL);
	Assert::same( 150, $image->width );

	Assert::same( 89, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// flipping Y shrink
	$image->resize(NULL, -150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );

	Assert::same( 104, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	// flipping X Y shrink
	$image->resize(-300, -150, Image::SHRINK_ONLY);
	Assert::same( 176, $image->width );

	Assert::same( 104, $image->height );
});



test(function() use ($main) {
	$image = clone $main;
	$image->resize(300, 150, Image::EXACT);
	Assert::same( 300, $image->width );

	Assert::same( 150, $image->height );
});
