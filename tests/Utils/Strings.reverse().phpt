<?php

/**
 * Test: Nette\Utils\Strings::reverse()
 *
 * @author     bojovyletoun
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


$t1 = "\x60,\xc8\xb6,\xe0\xb8\xa2,\xf0\xa0\x81\xa2";
$t2 = "\xf0\xa0\x81\xa2,\xe0\xb8\xa2,\xc8\xb6,\x60";
$r1 = Strings::reverse($t1);
$r2 = Strings::reverse($t2);

Assert::same($t1, $r2);
Assert::same($t2, $r1);
