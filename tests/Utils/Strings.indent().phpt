<?php

/**
 * Test: Nette\Utils\Strings::indent()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( "",  Strings::indent("") );
Assert::same( "\n",  Strings::indent("\n") );
Assert::same( "\tword",  Strings::indent("word") );
Assert::same( "\n\tword",  Strings::indent("\nword") );
Assert::same( "\n\tword",  Strings::indent("\nword") );
Assert::same( "\n\tword\n",  Strings::indent("\nword\n") );
Assert::same( "\r\n\tword\r\n",  Strings::indent("\r\nword\r\n") );
Assert::same( "\r\n\t\tword\r\n",  Strings::indent("\r\nword\r\n", 2) );
Assert::same( "\r\n      word\r\n",  Strings::indent("\r\nword\r\n", 2, '   ') );
