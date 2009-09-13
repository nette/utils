<?php

/**
 * Test: Image factories.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Image;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$image = Image::fromFile('images/logo.gif');
section("logo.gif");
dump( $image->width, 'width' );
dump( $image->height, 'height' );

$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
section("blank");
dump( $image->width, 'width' );
dump( $image->height, 'height' );



__halt_compiler();

------EXPECT------
==> logo.gif

width: int(176)

height: int(104)

==> blank

width: int(200)

height: int(300)
