<?php

/**
 * Test: Nette\Image flip.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Image;



require __DIR__ . '/../bootstrap.php';


$image = Image::fromFile('images/logo.gif');
$flipped = $image->resize(-100, -100);

Assert::same(file_get_contents(__DIR__ . '/Image.flip.expect'), $flipped->toString(Image::GIF));
