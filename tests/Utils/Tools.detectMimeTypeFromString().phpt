<?php

/**
 * Test: Nette\Tools::detectMimeTypeFromString()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Tools;



require __DIR__ . '/../bootstrap.php';



Assert::same( 'image/gif', Tools::detectMimeTypeFromString(file_get_contents('files/images/logo.gif')) );
Assert::same( 'application/octet-stream', Tools::detectMimeTypeFromString(file_get_contents('files/bad.ppt')) );
