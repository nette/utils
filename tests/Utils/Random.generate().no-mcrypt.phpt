<?php

/**
 * Test: Nette\Utils\Random::generate() without openssl and mcrypt
 * @phpIni disable_functions=openssl_random_pseudo_bytes,mcrypt_create_iv
 */

use Nette\Utils\Random;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


if (!extension_loaded('mcrypt')) {
	Tester\Environment::skip('Requires PHP extension mcrypt.');
}


Assert::same(10, strlen(Random::generate()));
Assert::same(0, strlen(Random::generate(0)));
Assert::same(5, strlen(Random::generate(5)));
Assert::same(200, strlen(Random::generate(200)));

Assert::truthy(preg_match('#^[0-9a-z]+$#', Random::generate()));
Assert::truthy(preg_match('#^[0-9]+$#', Random::generate(1000, '0-9')));
Assert::truthy(preg_match('#^[0a-z12]+$#', Random::generate(1000, '0a-z12')));
Assert::truthy(preg_match('#^[-a]+$#', Random::generate(1000, '-a')));
Assert::truthy(preg_match('#^[0]+$#', Random::generate(1000, '000')));

// frequency check
$length = 1e6;
$delta = 0.1;
$s = Nette\Utils\Random::generate($length, "\x01-\xFF");
$freq = count_chars($s);
Assert::same(0, $freq[0]);
for ($i = 1; $i < 255; $i++) {
	Assert::true($freq[$i] < $length / 255 * (1 + $delta) && $freq[$i] > $length / 255 * (1 - $delta));
}
