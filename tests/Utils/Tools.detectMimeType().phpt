<?php

/**
 * Test: Nette\Tools::detectMimeType()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Tools;



require __DIR__ . '/../initialize.php';



Assert::same( 'image/gif', Tools::detectMimeType('files/images/logo.gif') );
Assert::same( 'application/octet-stream', Tools::detectMimeType('files/bad.ppt') );
