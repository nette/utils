<?php

/**
 * Test: Nette\Utils\Strings::reverse()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$t1 = "\x60,\u{236},\u{E22},\u{20062}";
$t2 = "\u{20062},\u{E22},\u{236},\x60";
$r1 = Strings::reverse($t1);
$r2 = Strings::reverse($t2);

Assert::same($t1, $r2);
Assert::same($t2, $r1);

Assert::same("ana\u{F1}am", Strings::reverse("ma\u{F1}ana"));   // mañana -> anañam, U+00F1
Assert::same("ana\u{303}nam", Strings::reverse("man\u{303}ana")); // mañana -> anãnam, U+006E + U+0303 (combining character)
