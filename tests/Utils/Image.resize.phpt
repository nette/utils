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
width: int(176)

height: int(104)

cropping...

width: int(50)

height: int(84)

resizing X

width: int(150)

height: int(89)

resizing Y

width: int(176)

height: int(104)

resizing X Y

width: int(176)

height: int(104)

resizing X Y enlarge

width: int(254)

height: int(150)

resizing X Y enlarge stretch

width: int(300)

height: int(100)

resizing X Y stretch

width: int(176)

height: int(100)

resizing X%

width: int(194)

height: int(115)

resizing X% Y%

width: int(194)

height: int(94)

flipping X

width: int(150)

height: int(89)

flipping Y

width: int(176)

height: int(104)

flipping X Y

width: int(176)

height: int(104)
