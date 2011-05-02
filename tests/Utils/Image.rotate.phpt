<?php

/**
 * Test: Nette\Image rotating.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Image;



require __DIR__ . '/../bootstrap.php';



if (GD_BUNDLED === 0) {
	TestHelpers::skip('Requires PHP extension GD in bundled version.');
}



$image = Image::fromFile('images/logo.gif');
$image->rotate(30, Image::rgb(0, 0, 0));

Assert::same(file_get_contents(__DIR__ . '/Image.rotate.expect'), $image->toString(Image::GIF));
