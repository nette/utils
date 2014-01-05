<?php

/**
 * Test: Nette\Image cloning.
 *
 * @author     MzK Olda Salek
 */

use Nette\Image,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('gd')) {
	Tester\Environment::skip('Requires PHP extension GD.');
}


$original = Image::fromFile(__DIR__.'/images/logo.gif');

$dolly = clone $original;
Assert::notSame( $dolly->getImageResource(), $original->getImageResource() );
Assert::same( $dolly->toString(Image::GIF), $original->toString(Image::GIF) );
