<?php

/**
 * Test: Nette\Image crop, resize & flip.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Image;



require __DIR__ . '/../initialize.php';



$main = Image::fromFile('images/logo.gif');
$image = clone $main;
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

T::note("cropping...");
$image->crop(10, 20, 50, 300);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );


$image = clone $main;
T::note("resizing X");
$image->resize(150, NULL);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing Y");
$image->resize(NULL, 150);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing X Y");
$image->resize(300, 150);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing X Y enlarge");
$image->resize(300, 150, Image::ENLARGE);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing X Y enlarge stretch");
$image->resize(300, 100, Image::ENLARGE | Image::STRETCH);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing X Y stretch");
$image->resize(300, 100, Image::STRETCH);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing X%");
$image->resize('110%', NULL);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("resizing X% Y%");
$image->resize('110%', '90%');
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("flipping X");
$image->resize(-150, NULL);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("flipping Y");
$image->resize(NULL, -150);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = clone $main;
T::note("flipping X Y");
$image->resize(-300, -150);
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );



__halt_compiler() ?>

------EXPECT------
width: 176

height: 104

cropping...

width: 50

height: 84

resizing X

width: 150

height: 89

resizing Y

width: 176

height: 104

resizing X Y

width: 176

height: 104

resizing X Y enlarge

width: 254

height: 150

resizing X Y enlarge stretch

width: 300

height: 100

resizing X Y stretch

width: 176

height: 100

resizing X%

width: 194

height: 115

resizing X% Y%

width: 194

height: 94

flipping X

width: 150

height: 89

flipping Y

width: 176

height: 104

flipping X Y

width: 176

height: 104
