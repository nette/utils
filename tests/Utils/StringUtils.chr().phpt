<?php

/**
 * Test: Nette\StringUtils::chr()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( "\x00",  StringUtils::chr(0), '#0' );
Assert::same( "\xc3\xbf",  StringUtils::chr(255), '#255 in UTF-8' );
Assert::same( "\xFF",  StringUtils::chr(255, 'ISO-8859-1'), '#255 in ISO-8859-1' );
