<?php

/**
 * Test: Nette\Utils\Json::decodeSafe() collision attack.
 */

use Nette\Utils\Json,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$factor = (1 << 20);

$s = '{';
for ($i = 0; $i < 100000; $i++) {
	if ($i !== 0) {
		$s .= ',';
	}
	$s .= '"' . ($i * $factor) . '":0';
}
$s .= '}';


$elapsedTime = -microtime(true);

Assert::exception(function() use ($s) {
	Json::decodeSafe($s, Json::FORCE_ARRAY);
}, 'Nette\Utils\JsonException', 'Time limit exceeded');

$elapsedTime += microtime(true);

Assert::true($elapsedTime < 3.0);
