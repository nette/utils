<?php

/**
 * Test: Nette\Image crop & resize.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Image;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$main = Image::fromFile('images/logo.gif');
$image = clone $main;
dump( $image->width, 'width' );
dump( $image->height, 'height' );

output("cropping...");
$image->crop(10, 20, 50, 300);
dump( $image->width, 'width' );
dump( $image->height, 'height' );


$image = clone $main;
output("resizing X");
$image->resize(150, NULL);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing Y");
$image->resize(NULL, 150);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing X Y");
$image->resize(300, 150);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing X Y enlarge");
$image->resize(300, 150, Image::ENLARGE);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing X Y enlarge stretch");
$image->resize(300, 100, Image::ENLARGE | Image::STRETCH);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing X Y stretch");
$image->resize(300, 100, Image::STRETCH);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing X%");
$image->resize('110%', NULL);
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = clone $main;
output("resizing X% Y%");
$image->resize('110%', '90%');
dump( $image->width, 'width' );
dump( $image->height, 'height' );



__halt_compiler();

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
