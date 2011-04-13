<?php

/**
 * Test: Nette\StringUtils::endsWith()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::true( StringUtils::endsWith('123', NULL), "endsWith('123', NULL)" );
Assert::true( StringUtils::endsWith('123', ''), "endsWith('123', '')" );
Assert::true( StringUtils::endsWith('123', '3'), "endsWith('123', '3')" );
Assert::false( StringUtils::endsWith('123', '2'), "endsWith('123', '2')" );
Assert::true( StringUtils::endsWith('123', '123'), "endsWith('123', '123')" );
Assert::false( StringUtils::endsWith('123', '1234'), "endsWith('123', '1234')" );
