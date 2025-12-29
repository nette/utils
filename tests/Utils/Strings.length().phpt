<?php

/**
 * Test: Nette\Utils\Strings::length()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('returns zero for empty string', function () {
	Assert::same(0, Strings::length(''));
});


test('handles ASCII strings', function () {
	Assert::same(5, Strings::length('hello'));
	Assert::same(13, Strings::length('Hello, World!'));
});


test('counts UTF-8 characters correctly', function () {
	// Iñtërnâtiônàlizætiøn
	Assert::same(20, Strings::length("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n"));
	Assert::same(1, Strings::length("\u{10000}")); // U+010000
});


test('counts precomposed characters as single unit', function () {
	Assert::same(6, Strings::length("ma\u{F1}ana")); // mañana, U+00F1
});


test('counts combining characters separately', function () {
	// mañana, U+006E + U+0303 (combining character)
	Assert::same(7, Strings::length("man\u{303}ana"));
});


test('handles emoji and special symbols', function () {
	// Emoji are counted as single code points (even if multiple bytes)
	Assert::same(1, Strings::length('😀'));
	Assert::same(6, Strings::length('Hello😀'));
});


test('handles various Unicode ranges', function () {
	Assert::same(4, Strings::length('中文字符')); // Chinese characters
	Assert::same(6, Strings::length('Привет')); // Cyrillic
	Assert::same(5, Strings::length('مرحبا')); // Arabic
});
