<?php

/**
 * Test: Nette\Utils\Strings::normalizeNewLines()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( "\n \n \n\n",  Strings::normalizeNewLines("\r\n \r \n\n") );
Assert::same( "\n\n",  Strings::normalizeNewLines("\n\r") );
