<?php

/**
 * Test: Nette\Image rotating.
 *
 * @author     David Grudl
 * @package    Nette
 */

use Nette\Image;



require __DIR__ . '/../bootstrap.php';



if (!extension_loaded('gd') || GD_BUNDLED === 0) {
	Tester\Helpers::skip('Requires PHP extension GD (the bundled version).');
}



$image = Image::fromFile('images/logo.gif');
$image->rotate(30, Image::rgb(0, 0, 0));

Assert::same(file_get_contents(__DIR__ . '/Image.rotate.expect'), $image->toString(Image::PNG, 0));
