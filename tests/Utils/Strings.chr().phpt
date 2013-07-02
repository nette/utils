<?php

/**
 * Test: Nette\Utils\Strings::chr()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( "\x00",  Strings::chr(0) ); // #0
Assert::same( "\xc3\xbf",  Strings::chr(255) ); // #255 in UTF-8
Assert::same( "\xFF",  Strings::chr(255, 'ISO-8859-1') ); // #255 in ISO-8859-1
