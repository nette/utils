<?php

/**
 * Test: Nette\String::indent()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../bootstrap.php';



Assert::same( "",  String::indent("") );
Assert::same( "\n",  String::indent("\n") );
Assert::same( "\tword",  String::indent("word") );
Assert::same( "\n\tword",  String::indent("\nword") );
Assert::same( "\n\tword",  String::indent("\nword") );
Assert::same( "\n\tword\n",  String::indent("\nword\n") );
Assert::same( "\r\n\tword\r\n",  String::indent("\r\nword\r\n") );
Assert::same( "\r\n\t\tword\r\n",  String::indent("\r\nword\r\n", 2) );
Assert::same( "\r\n      word\r\n",  String::indent("\r\nword\r\n", 2, '   ') );
