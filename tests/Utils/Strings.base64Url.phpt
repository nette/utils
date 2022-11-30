<?php

/**
 * Test: Nette\Utils\Strings::base64UrlEncode() & base64UrlDecode()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = "\x03\xe0\x7f\x08";
Assert::same('A+B/CA==', base64_encode($input));
Assert::same('A-B_CA', Strings::base64UrlEncode($input));
Assert::same($input, Strings::base64UrlDecode('A-B_CA'));

Assert::exception(
	fn() => Strings::base64UrlDecode('A'),
	Nette\InvalidArgumentException::class,
	'Invalid base64url string length.',
);

Assert::exception(
	fn() => Strings::base64UrlDecode('A#'),
	Nette\InvalidArgumentException::class,
	'Invalid base64 string.',
);
