<?php

/**
 * Test: Nette\Image factories.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Image;



require __DIR__ . '/../initialize.php';



$image = Image::fromFile('images/logo.gif');
T::note("logo.gif");
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );

$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
T::note("blank");
T::dump( $image->width, 'width' );
T::dump( $image->height, 'height' );



__halt_compiler() ?>

------EXPECT------
logo.gif

width: 176

height: 104

blank

width: 200

height: 300
