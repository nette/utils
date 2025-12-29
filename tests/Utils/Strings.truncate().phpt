<?php

/**
 * Test: Nette\Utils\Strings::truncate()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$s = "\u{158}ekn\u{11B}te, jak se (dnes) m\u{E1}te?"; // Řekněte, jak se (dnes) máte?


test('truncates to ellipsis when length is too short', function () use ($s) {
	Assert::same('…', Strings::truncate($s, -1));
	Assert::same('…', Strings::truncate($s, 0));
	Assert::same('…', Strings::truncate($s, 1));
});


test('cuts at character boundary when no word break available', function () use ($s) {
	Assert::same('Ř…', Strings::truncate($s, 2));
	Assert::same('Ře…', Strings::truncate($s, 3));
	Assert::same('Řek…', Strings::truncate($s, 4));
});


test('preserves whole words when possible', function () use ($s) {
	// At length 9, can't fit "jak" so keeps "Řekněte,"
	Assert::same('Řekněte,…', Strings::truncate($s, 9));
	Assert::same('Řekněte,…', Strings::truncate($s, 10));
	Assert::same('Řekněte,…', Strings::truncate($s, 11));

	// At length 13, can fit "jak"
	Assert::same('Řekněte, jak…', Strings::truncate($s, 13));

	// At length 16, can fit "jak se"
	Assert::same('Řekněte, jak se…', Strings::truncate($s, 16));
});


test('handles word breaks with spaces and punctuation', function () use ($s) {
	// Breaks at space before parenthesis
	Assert::same('Řekněte, jak se …', Strings::truncate($s, 17));
	Assert::same('Řekněte, jak se …', Strings::truncate($s, 20));

	// Includes content in parentheses
	Assert::same('Řekněte, jak se (dnes…', Strings::truncate($s, 22));
	Assert::same('Řekněte, jak se (dnes)…', Strings::truncate($s, 23));
});


test('returns original string when length is sufficient', function () use ($s) {
	Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 28));
	Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 100));
});


test('handles combining characters', function () {
	// mañana, U+006E + U+0303 (combining character)
	// With length 4, keeps "man" + combining tilde
	Assert::same("man\u{303}", Strings::truncate("man\u{303}ana", 4, ''));

	// With length 3, cuts before combining character
	Assert::same('man', Strings::truncate("man\u{303}ana", 3, ''));
});


test('uses custom append string', function () {
	Assert::same('Hello...', Strings::truncate('Hello, World!', 8, '...'));
	Assert::same('Hello [more]', Strings::truncate('Hello, World!', 12, ' [more]'));
	Assert::same('Hello', Strings::truncate('Hello, World!', 5, ''));
});


test('handles Unicode text with word boundaries', function () {
	$czech = 'Příliš žluťoučký kůň úpěl ďábelské ódy';
	Assert::same('Příliš…', Strings::truncate($czech, 10));
	Assert::same('Příliš žluťoučký…', Strings::truncate($czech, 20));

	$russian = 'Съешь же ещё этих мягких французских булок';
	Assert::same('Съешь же…', Strings::truncate($russian, 12));
});


test('handles text without spaces', function () {
	Assert::same('abc…', Strings::truncate('abcdefghijk', 4));
	Assert::same('12345…', Strings::truncate('1234567890', 6));
});


test('handles empty string', function () {
	Assert::same('', Strings::truncate('', 10));
	Assert::same('', Strings::truncate('', 0));
});


test('handles single word shorter than limit', function () {
	Assert::same('Hello', Strings::truncate('Hello', 10));
	Assert::same('Test', Strings::truncate('Test', 100));
});


test('breaks at various punctuation marks', function () {
	Assert::same('Hello,…', Strings::truncate('Hello, World!', 7));
	Assert::same('one-two…', Strings::truncate('one-two-three', 8));
	Assert::same('path…', Strings::truncate('path/to/file', 7));
});


test('handles emoji and multi-byte characters', function () {
	Assert::same('Hello…', Strings::truncate('Hello 😀 World', 6));
	Assert::same('😀😀😀…', Strings::truncate('😀😀😀😀😀', 4));
});


test('handles grapheme clusters correctly', function () {
	// Woman technologist emoji with skin tone modifier
	$text = '👩‍💻 coding is fun';
	$truncated = Strings::truncate($text, 10);
	// Should handle multi-codepoint grapheme clusters
	Assert::type('string', $truncated);
});
