<?php

/**
 * Test: Nette\String::chr()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../NetteTest/initialize.php';



Assert::same( "\x00",  String::chr(0), '#0' );
Assert::same( "\xc3\xbf",  String::chr(255), '#255 in UTF-8' );
Assert::same( "\xFF",  String::chr(255, 'ISO-8859-1'), '#255 in ISO-8859-1' );
