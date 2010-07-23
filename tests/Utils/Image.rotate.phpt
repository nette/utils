<?php

/**
 * Test: Nette\Image rotating.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Image;



require __DIR__ . '/../initialize.php';



if (GD_BUNDLED === 0) {
	T::skip('Requires PHP extension GD in bundled version.');
}



$image = Image::fromFile('images/logo.gif');
$image->rotate(30, Image::rgb(0, 0, 0));
$image->send(Image::GIF);



__halt_compiler() ?>
