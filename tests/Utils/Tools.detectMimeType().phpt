<?php

/**
 * Test: Nette\Tools::detectMimeType()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Tools;



require __DIR__ . '/../initialize.php';



Assert::same( "image/gif", Tools::detectMimeType('images/logo.gif') );
Assert::same( "application/octet-stream", Tools::detectMimeType('files/bad.ppt') );
