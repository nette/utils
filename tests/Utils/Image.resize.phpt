<?php

/**
 * Test: Nette\Image crop, resize & flip.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Image;



require __DIR__ . '/../initialize.php';



$main = Image::fromFile('images/logo.gif');
$image = clone $main;
Assert::same( 176, $image->width, 'width' );

Assert::same( 104, $image->height, 'height' );


// cropping...
$image->crop(10, 20, 50, 300);
Assert::same( 50, $image->width, 'width' );

Assert::same( 84, $image->height, 'height' );



$image = clone $main;
// resizing X
$image->resize(150, NULL);
Assert::same( 150, $image->width, 'width' );

Assert::same( 89, $image->height, 'height' );


$image = clone $main;
// resizing Y
$image->resize(NULL, 150);
Assert::same( 176, $image->width, 'width' );

Assert::same( 104, $image->height, 'height' );


$image = clone $main;
// resizing X Y
$image->resize(300, 150);
Assert::same( 176, $image->width, 'width' );

Assert::same( 104, $image->height, 'height' );


$image = clone $main;
// resizing X Y enlarge
$image->resize(300, 150, Image::ENLARGE);
Assert::same( 254, $image->width, 'width' );

Assert::same( 150, $image->height, 'height' );


$image = clone $main;
// resizing X Y enlarge stretch
$image->resize(300, 100, Image::ENLARGE | Image::STRETCH);
Assert::same( 300, $image->width, 'width' );

Assert::same( 100, $image->height, 'height' );


$image = clone $main;
// resizing X Y stretch
$image->resize(300, 100, Image::STRETCH);
Assert::same( 176, $image->width, 'width' );

Assert::same( 100, $image->height, 'height' );


$image = clone $main;
// resizing X%
$image->resize('110%', NULL);
Assert::same( 194, $image->width, 'width' );

Assert::same( 115, $image->height, 'height' );


$image = clone $main;
// resizing X% Y%
$image->resize('110%', '90%');
Assert::same( 194, $image->width, 'width' );

Assert::same( 94, $image->height, 'height' );


$image = clone $main;
// flipping X
$image->resize(-150, NULL);
Assert::same( 150, $image->width, 'width' );

Assert::same( 89, $image->height, 'height' );


$image = clone $main;
// flipping Y
$image->resize(NULL, -150);
Assert::same( 176, $image->width, 'width' );

Assert::same( 104, $image->height, 'height' );


$image = clone $main;
// flipping X Y
$image->resize(-300, -150);
Assert::same( 176, $image->width, 'width' );

Assert::same( 104, $image->height, 'height' );
