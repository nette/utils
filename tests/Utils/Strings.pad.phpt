<?php

/**
 * Test: Nette\Utils\Strings::padLeft() & padRight()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('padLeft with ASCII strings', function () {
	Assert::same('00042', Strings::padLeft('42', 5, '0'));
	Assert::same('xxxhello', Strings::padLeft('hello', 8, 'x'));
	Assert::same('abcabcabc123', Strings::padLeft('123', 12, 'abc'));
});


test('padRight with ASCII strings', function () {
	Assert::same('42000', Strings::padRight('42', 5, '0'));
	Assert::same('helloxxx', Strings::padRight('hello', 8, 'x'));
	Assert::same('123abcabcabc', Strings::padRight('123', 12, 'abc'));
});


test('padLeft with empty string', function () {
	Assert::same('-----', Strings::padLeft('', 5, '-'));
	Assert::same('     ', Strings::padLeft('', 5));
	Assert::same('', Strings::padLeft('', 0, '-'));
});


test('padRight with empty string', function () {
	Assert::same('-----', Strings::padRight('', 5, '-'));
	Assert::same('     ', Strings::padRight('', 5));
	Assert::same('', Strings::padRight('', 0, '-'));
});


test('padLeft with exact length', function () {
	Assert::same('hello', Strings::padLeft('hello', 5, 'x'));
	Assert::same('test', Strings::padLeft('test', 4, '0'));
});


test('padRight with exact length', function () {
	Assert::same('hello', Strings::padRight('hello', 5, 'x'));
	Assert::same('test', Strings::padRight('test', 4, '0'));
});


test('padLeft with longer padding than needed', function () {
	Assert::same('abchi', Strings::padLeft('hi', 5, 'abcdefgh'));
	Assert::same('xyztest', Strings::padLeft('test', 7, 'xyzuvw'));
});


test('padRight with longer padding than needed', function () {
	Assert::same('hiabc', Strings::padRight('hi', 5, 'abcdefgh'));
	Assert::same('testxyz', Strings::padRight('test', 7, 'xyzuvw'));
});


test('padLeft with multi-byte padding string', function () {
	// ŽLU padded with ŤOU
	Assert::same('ŤOUŤOUŤŽLU', Strings::padLeft("\u{17D}LU", 10, "\u{164}OU"));
	Assert::same('ŤOUŤOUŽLU', Strings::padLeft("\u{17D}LU", 9, "\u{164}OU"));
});


test('padLeft returns original when length is reached', function () {
	Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", 3, "\u{164}OU"));
	Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", 0, "\u{164}OU"));
	Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", -1, "\u{164}OU"));
});


test('padLeft with single multi-byte character', function () {
	Assert::same('ŤŤŤŤŤŤŤŽLU', Strings::padLeft("\u{17D}LU", 10, "\u{164}"));
	Assert::same('ŽLU', Strings::padLeft("\u{17D}LU", 3, "\u{164}"));
});


test('padLeft with default space padding', function () {
	Assert::same('       ŽLU', Strings::padLeft("\u{17D}LU", 10));
	Assert::same('     hello', Strings::padLeft('hello', 10));
});


test('padRight with multi-byte padding string', function () {
	Assert::same('ŽLUŤOUŤOUŤ', Strings::padRight("\u{17D}LU", 10, "\u{164}OU"));
	Assert::same('ŽLUŤOUŤOU', Strings::padRight("\u{17D}LU", 9, "\u{164}OU"));
});


test('padRight returns original when length is reached', function () {
	Assert::same('ŽLU', Strings::padRight("\u{17D}LU", 3, "\u{164}OU"));
	Assert::same('ŽLU', Strings::padRight("\u{17D}LU", 0, "\u{164}OU"));
	Assert::same('ŽLU', Strings::padRight("\u{17D}LU", -1, "\u{164}OU"));
});


test('padRight with single multi-byte character', function () {
	Assert::same('ŽLUŤŤŤŤŤŤŤ', Strings::padRight("\u{17D}LU", 10, "\u{164}"));
	Assert::same('ŽLU', Strings::padRight("\u{17D}LU", 3, "\u{164}"));
});


test('padRight with default space padding', function () {
	Assert::same('ŽLU       ', Strings::padRight("\u{17D}LU", 10));
	Assert::same('hello     ', Strings::padRight('hello', 10));
});


test('padLeft with emoji', function () {
	Assert::same('😀😀😀hi', Strings::padLeft('hi', 5, '😀'));
	Assert::same('😀😀test', Strings::padLeft('test', 6, '😀'));
});


test('padRight with emoji', function () {
	Assert::same('hi😀😀😀', Strings::padRight('hi', 5, '😀'));
	Assert::same('test😀😀', Strings::padRight('test', 6, '😀'));
});


test('padLeft handles combining characters', function () {
	// man + combining tilde = mañana
	Assert::same('..man', Strings::padLeft('man', 5, '.'));
	Assert::same("..man\u{303}", Strings::padLeft("man\u{303}", 6, '.'));
});


test('padRight handles combining characters', function () {
	Assert::same('man..', Strings::padRight('man', 5, '.'));
	Assert::same("man\u{303}..", Strings::padRight("man\u{303}", 6, '.'));
});
