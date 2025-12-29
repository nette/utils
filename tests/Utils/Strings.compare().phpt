<?php

/**
 * Test: Nette\Utils\Strings::compare()
 * @phpExtension mbstring
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('compares empty strings', function () {
	Assert::true(Strings::compare('', ''));
	Assert::true(Strings::compare('', '', 0));
	Assert::true(Strings::compare('', '', 1));
	Assert::true(Strings::compare('', '', -1));
});


test('compares identical strings', function () {
	Assert::true(Strings::compare('hello', 'hello'));
	Assert::true(Strings::compare('test', 'test', 10));
});


test('case-insensitive comparison by default', function () {
	Assert::true(Strings::compare('Hello', 'HELLO'));
	Assert::true(Strings::compare('Test', 'test'));
	Assert::true(Strings::compare('ABC', 'abc'));
});


test('full string comparison', function () {
	Assert::false(Strings::compare('xy', 'xx'));
	Assert::false(Strings::compare('hello', 'world'));
	Assert::false(Strings::compare('test', 'testing'));
});


test('compares with length limit', function () {
	Assert::true(Strings::compare('xy', 'xx', 0));
	Assert::true(Strings::compare('xy', 'xx', 1));
	Assert::false(Strings::compare('xy', 'yy', 1));
});


test('compares from end with negative length', function () {
	Assert::true(Strings::compare('xy', 'yy', -1));
	Assert::true(Strings::compare('abc', 'xbc', -2));
	Assert::false(Strings::compare('abc', 'xyz', -2));
});


test('compares Unicode strings case-insensitively', function () {
	// Iñtërnâtiônàlizætiøn
	Assert::true(
		Strings::compare(
			"I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n",
			"I\u{D1}T\u{CB}RN\u{C2}TI\u{D4}N\u{C0}LIZ\u{C6}TI\u{D8}N",
		),
	);

	Assert::true(
		Strings::compare(
			"I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n",
			"I\u{D1}T\u{CB}RN\u{C2}TI\u{D4}N\u{C0}LIZ\u{C6}TI\u{D8}N",
			10,
		),
	);
});


test('compares NFC and NFD Unicode normalization forms', function () {
	if (!class_exists('Normalizer')) {
		Tester\Environment::skip('Normalizer class not available');
	}

	// Å (U+00C5) vs A (U+0041) + ̊ (U+030A)
	Assert::true(Strings::compare("\xC3\x85", "A\xCC\x8A"), 'comparing NFC with NFD form');
	Assert::true(Strings::compare("A\xCC\x8A", "\xC3\x85"), 'comparing NFD with NFC form');
});


test('compares with zero length', function () {
	Assert::true(Strings::compare('completely', 'different', 0));
	Assert::true(Strings::compare('any', 'thing', 0));
});


test('handles Czech and other diacritics', function () {
	Assert::true(Strings::compare('Příliš', 'PŘÍLIŠ'));
	Assert::true(Strings::compare('žluťoučký', 'ŽLUŤOUČKÝ'));
	Assert::true(Strings::compare('kůň', 'KŮŇ'));
});


test('compares Cyrillic characters', function () {
	Assert::true(Strings::compare('Привет', 'ПРИВЕТ'));
	Assert::true(Strings::compare('мир', 'МИР'));
});


test('partial comparison from beginning', function () {
	Assert::true(Strings::compare('hello world', 'hello universe', 5));
	Assert::false(Strings::compare('hello world', 'hi world', 2));
});


test('partial comparison from end', function () {
	Assert::true(Strings::compare('hello world', 'hey world', -5));
	Assert::false(Strings::compare('hello world', 'hello mars', -4));
});


test('handles strings with different lengths', function () {
	Assert::false(Strings::compare('short', 'much longer string'));
	Assert::true(Strings::compare('short', 'SHORE', 4));
	Assert::false(Strings::compare('short', 'SHORE', 5));
});


test('handles special characters', function () {
	Assert::true(Strings::compare('test@example.com', 'TEST@EXAMPLE.COM'));
	Assert::true(Strings::compare('path/to/file', 'PATH/TO/FILE'));
	Assert::true(Strings::compare('hello-world', 'HELLO-WORLD'));
});


test('compares with emoji and symbols', function () {
	Assert::true(Strings::compare('hello 😀', 'hello 😀'));
	Assert::false(Strings::compare('😀', '😁'));
});


test('edge case with very long strings', function () {
	$long1 = str_repeat('a', 1000);
	$long2 = str_repeat('A', 1000);

	Assert::true(Strings::compare($long1, $long2));
	Assert::true(Strings::compare($long1, $long2, 500));
	Assert::true(Strings::compare($long1, $long2, -500));
});


test('handles null bytes in strings', function () {
	Assert::true(Strings::compare("test\x00string", "TEST\x00STRING"));
	Assert::false(Strings::compare("test\x00", "test\x01"));
});


test('single character comparison', function () {
	Assert::true(Strings::compare('a', 'A'));
	Assert::false(Strings::compare('a', 'b'));
	Assert::true(Strings::compare('a', 'b', 0));
});


test('comparison with whitespace', function () {
	Assert::false(Strings::compare('hello', ' hello'));
	Assert::false(Strings::compare('hello ', 'hello'));
	Assert::true(Strings::compare('  ', '  '));
});
