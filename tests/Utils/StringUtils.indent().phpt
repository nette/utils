<?php

/**
 * Test: Nette\StringUtils::indent()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( "",  StringUtils::indent("") );
Assert::same( "\n",  StringUtils::indent("\n") );
Assert::same( "\tword",  StringUtils::indent("word") );
Assert::same( "\n\tword",  StringUtils::indent("\nword") );
Assert::same( "\n\tword",  StringUtils::indent("\nword") );
Assert::same( "\n\tword\n",  StringUtils::indent("\nword\n") );
Assert::same( "\r\n\tword\r\n",  StringUtils::indent("\r\nword\r\n") );
Assert::same( "\r\n\t\tword\r\n",  StringUtils::indent("\r\nword\r\n", 2) );
Assert::same( "\r\n      word\r\n",  StringUtils::indent("\r\nword\r\n", 2, '   ') );
