<?php

/**
 * Test: Nette\Utils\Strings::endsWith()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::true( Strings::endsWith('123', NULL), "endsWith('123', NULL)" );
Assert::true( Strings::endsWith('123', ''), "endsWith('123', '')" );
Assert::true( Strings::endsWith('123', '3'), "endsWith('123', '3')" );
Assert::false( Strings::endsWith('123', '2'), "endsWith('123', '2')" );
Assert::true( Strings::endsWith('123', '123'), "endsWith('123', '123')" );
Assert::false( Strings::endsWith('123', '1234'), "endsWith('123', '1234')" );
