<?php

/**
 * Test: Nette\Image alpha channel.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Image;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$image = Image::fromBlank(rand(100, 200), 100, Image::rgb(255, 128, 0, 60));
$image->crop(0, 0, '60%', '60%');
$image->savealpha(TRUE);
$image->send(Image::PNG, 100);



__halt_compiler() ?>
