<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;
use function is_array, is_object, strlen;


/**
 * String tools library.
 */
class Strings
{
	use Nette\StaticClass;

	public const TRIM_CHARACTERS = " \t\n\r\0\x0B\u{A0}";


	/**
	 * Checks if the string is valid for UTF-8 encoding.
	 */
	public static function checkEncoding(string $s): bool
	{
		return $s === self::fixEncoding($s);
	}


	/**
	 * Removes invalid code unit sequences from UTF-8 string.
	 */
	public static function fixEncoding(string $s): string
	{
		// removes xD800-xDFFF, x110000 and higher
		return htmlspecialchars_decode(htmlspecialchars($s, ENT_NOQUOTES | ENT_IGNORE, 'UTF-8'), ENT_NOQUOTES);
	}


	/**
	 * Returns a specific character in UTF-8 from code point (0x0 to 0xD7FF or 0xE000 to 0x10FFFF).
	 * @throws Nette\InvalidArgumentException if code point is not in valid range
	 */
	public static function chr(int $code): string
	{
		if ($code < 0 || ($code >= 0xD800 && $code <= 0xDFFF) || $code > 0x10FFFF) {
			throw new Nette\InvalidArgumentException('Code point must be in range 0x0 to 0xD7FF or 0xE000 to 0x10FFFF.');
		}
		return iconv('UTF-32BE', 'UTF-8//IGNORE', pack('N', $code));
	}


	/**
	 * Starts the $haystack string with the prefix $needle?
	 */
	public static function startsWith(string $haystack, string $needle): bool
	{
		return strncmp($haystack, $needle, strlen($needle)) === 0;
	}


	/**
	 * Ends the $haystack string with the suffix $needle?
	 */
	public static function endsWith(string $haystack, string $needle): bool
	{
		return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
	}


	/**
	 * Does $haystack contain $needle?
	 */
	public static function contains(string $haystack, string $needle): bool
	{
		return strpos($haystack, $needle) !== false;
	}


	/**
	 * Returns a part of UTF-8 string.
	 */
	public static function substring(string $s, int $start, int $length = null): string
	{
		if (function_exists('mb_substr')) {
			return mb_substr($s, $start, $length, 'UTF-8'); // MB is much faster
		} elseif ($length === null) {
			$length = self::length($s);
		} elseif ($start < 0 && $length < 0) {
			$start += self::length($s); // unifies iconv_substr behavior with mb_substr
		}
		return iconv_substr($s, $start, $length, 'UTF-8');
	}


	/**
	 * Removes special controls characters and normalizes line endings, spaces and normal form to NFC in UTF-8 string.
	 */
	public static function normalize(string $s): string
	{
		// convert to compressed normal form (NFC)
		if (class_exists('Normalizer', false)) {
			$s = \Normalizer::normalize($s, \Normalizer::FORM_C);
		}

		$s = self::normalizeNewLines($s);

		// remove control characters; leave \t + \n
		$s = preg_replace('#[\x00-\x08\x0B-\x1F\x7F-\x9F]+#u', '', $s);

		// right trim
		$s = preg_replace('#[\t ]+$#m', '', $s);

		// leading and trailing blank lines
		$s = trim($s, "\n");

		return $s;
	}


	/**
	 * Standardize line endings to unix-like.
	 */
	public static function normalizeNewLines(string $s): string
	{
		return str_replace(["\r\n", "\r"], "\n", $s);
	}


	/**
	 * Converts UTF-8 string to ASCII.
	 */
	public static function toAscii(string $s): string
	{
		static $transliterator = null;
		if ($transliterator === null && class_exists('Transliterator', false)) {
			$transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
		}

		$s = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{2FF}\x{370}-\x{10FFFF}]#u', '', $s);
		$s = strtr($s, '`\'"^~?', "\x01\x02\x03\x04\x05\x06");
		$s = str_replace(
			["\u{201E}", "\u{201C}", "\u{201D}", "\u{201A}", "\u{2018}", "\u{2019}", "\u{B0}"],
			["\x03", "\x03", "\x03", "\x02", "\x02", "\x02", "\x04"], $s
		);
		if ($transliterator !== null) {
			$s = $transliterator->transliterate($s);
		}
		if (ICONV_IMPL === 'glibc') {
			$s = str_replace(
				["\u{BB}", "\u{AB}", "\u{2026}", "\u{2122}", "\u{A9}", "\u{AE}"],
				['>>', '<<', '...', 'TM', '(c)', '(R)'], $s
			);
			$s = iconv('UTF-8', 'WINDOWS-1250//TRANSLIT//IGNORE', $s);
			$s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
				. "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
				. "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
				. "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe"
				. "\x96\xa0\x8b\x97\x9b\xa6\xad\xb7",
				'ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt- <->|-.');
			$s = preg_replace('#[^\x00-\x7F]++#', '', $s);
		} else {
			$s = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
		}
		$s = str_replace(['`', "'", '"', '^', '~', '?'], '', $s);
		return strtr($s, "\x01\x02\x03\x04\x05\x06", '`\'"^~?');
	}


	/**
	 * Converts UTF-8 string to web safe characters [a-z0-9-] text.
	 */
	public static function webalize(string $s, string $charlist = null, bool $lower = true): string
	{
		$s = self::toAscii($s);
		if ($lower) {
			$s = strtolower($s);
		}
		$s = preg_replace('#[^a-z0-9' . ($charlist !== null ? preg_quote($charlist, '#') : '') . ']+#i', '-', $s);
		$s = trim($s, '-');
		return $s;
	}


	/**
	 * Truncates UTF-8 string to maximal length.
	 */
	public static function truncate(string $s, int $maxLen, string $append = "\u{2026}"): string
	{
		if (self::length($s) > $maxLen) {
			$maxLen -= self::length($append);
			if ($maxLen < 1) {
				return $append;

			} elseif ($matches = self::match($s, '#^.{1,' . $maxLen . '}(?=[\s\x00-/:-@\[-`{-~])#us')) {
				return $matches[0] . $append;

			} else {
				return self::substring($s, 0, $maxLen) . $append;
			}
		}
		return $s;
	}


	/**
	 * Indents UTF-8 string from the left.
	 */
	public static function indent(string $s, int $level = 1, string $chars = "\t"): string
	{
		if ($level > 0) {
			$s = self::replace($s, '#(?:^|[\r\n]+)(?=[^\r\n])#', '$0' . str_repeat($chars, $level));
		}
		return $s;
	}


	/**
	 * Converts UTF-8 string to lower case.
	 */
	public static function lower(string $s): string
	{
		return mb_strtolower($s, 'UTF-8');
	}


	/**
	 * Converts first character to lower case.
	 */
	public static function firstLower(string $s): string
	{
		return self::lower(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Converts UTF-8 string to upper case.
	 */
	public static function upper(string $s): string
	{
		return mb_strtoupper($s, 'UTF-8');
	}


	/**
	 * Converts first character to upper case.
	 */
	public static function firstUpper(string $s): string
	{
		return self::upper(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Capitalizes UTF-8 string.
	 */
	public static function capitalize(string $s): string
	{
		return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
	}


	/**
	 * Case-insensitive compares UTF-8 strings.
	 */
	public static function compare(string $left, string $right, int $len = null): bool
	{
		if (class_exists('Normalizer', false)) {
			$left = \Normalizer::normalize($left, \Normalizer::FORM_D); // form NFD is faster
			$right = \Normalizer::normalize($right, \Normalizer::FORM_D); // form NFD is faster
		}

		if ($len < 0) {
			$left = self::substring($left, $len, -$len);
			$right = self::substring($right, $len, -$len);
		} elseif ($len !== null) {
			$left = self::substring($left, 0, $len);
			$right = self::substring($right, 0, $len);
		}
		return self::lower($left) === self::lower($right);
	}


	/**
	 * Finds the length of common prefix of strings.
	 * @param  string[]  $strings
	 */
	public static function findPrefix(array $strings): string
	{
		$first = array_shift($strings);
		for ($i = 0; $i < strlen($first); $i++) {
			foreach ($strings as $s) {
				if (!isset($s[$i]) || $first[$i] !== $s[$i]) {
					while ($i && $first[$i - 1] >= "\x80" && $first[$i] >= "\x80" && $first[$i] < "\xC0") {
						$i--;
					}
					return substr($first, 0, $i);
				}
			}
		}
		return $first;
	}


	/**
	 * Returns number of characters (not bytes) in UTF-8 string.
	 * That is the number of Unicode code points which may differ from the number of graphemes.
	 */
	public static function length(string $s): int
	{
		return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen(utf8_decode($s));
	}


	/**
	 * Strips whitespace from UTF-8 string.
	 */
	public static function trim(string $s, string $charlist = self::TRIM_CHARACTERS): string
	{
		$charlist = preg_quote($charlist, '#');
		return self::replace($s, '#^[' . $charlist . ']+|[' . $charlist . ']+$#Du', '');
	}


	/**
	 * Pad a UTF-8 string to a certain length with another string.
	 */
	public static function padLeft(string $s, int $length, string $pad = ' '): string
	{
		$length = max(0, $length - self::length($s));
		$padLen = self::length($pad);
		return str_repeat($pad, (int) ($length / $padLen)) . self::substring($pad, 0, $length % $padLen) . $s;
	}


	/**
	 * Pad a UTF-8 string to a certain length with another string.
	 */
	public static function padRight(string $s, int $length, string $pad = ' '): string
	{
		$length = max(0, $length - self::length($s));
		$padLen = self::length($pad);
		return $s . str_repeat($pad, (int) ($length / $padLen)) . self::substring($pad, 0, $length % $padLen);
	}


	/**
	 * Reverse string.
	 */
	public static function reverse(string $s): string
	{
		return iconv('UTF-32LE', 'UTF-8', strrev(iconv('UTF-8', 'UTF-32BE', $s)));
	}


	/**
	 * Returns part of $haystack before $nth occurence of $needle (negative value means searching from the end).
	 * @return string|null  returns null if the needle was not found
	 */
	public static function before(string $haystack, string $needle, int $nth = 1): ?string
	{
		$pos = self::pos($haystack, $needle, $nth);
		return $pos === null
			? null
			: substr($haystack, 0, $pos);
	}


	/**
	 * Returns part of $haystack after $nth occurence of $needle (negative value means searching from the end).
	 * @return string|null  returns null if the needle was not found
	 */
	public static function after(string $haystack, string $needle, int $nth = 1): ?string
	{
		$pos = self::pos($haystack, $needle, $nth);
		return $pos === null
			? null
			: substr($haystack, $pos + strlen($needle));
	}


	/**
	 * Returns position of $nth occurence of $needle in $haystack (negative value means searching from the end).
	 * @return int|null  offset in characters or null if the needle was not found
	 */
	public static function indexOf(string $haystack, string $needle, int $nth = 1): ?int
	{
		$pos = self::pos($haystack, $needle, $nth);
		return $pos === null
			? null
			: self::length(substr($haystack, 0, $pos));
	}


	/**
	 * Returns position of $nth occurence of $needle in $haystack.
	 * @return int|null  offset in bytes or null if the needle was not found
	 */
	private static function pos(string $haystack, string $needle, int $nth = 1): ?int
	{
		if (!$nth) {
			return null;
		} elseif ($nth > 0) {
			if ($needle === '') {
				return 0;
			}
			$pos = 0;
			while (($pos = strpos($haystack, $needle, $pos)) !== false && --$nth) {
				$pos++;
			}
		} else {
			$len = strlen($haystack);
			if ($needle === '') {
				return $len;
			}
			$pos = $len - 1;
			while (($pos = strrpos($haystack, $needle, $pos - $len)) !== false && ++$nth) {
				$pos--;
			}
		}
		return $pos === false ? null : $pos;
	}


	/**
	 * Splits string by a regular expression.
	 */
	public static function split(string $subject, string $pattern, int $flags = 0): array
	{
		return self::pcre('preg_split', [$pattern, $subject, -1, $flags | PREG_SPLIT_DELIM_CAPTURE]);
	}


	/**
	 * Performs a regular expression match. Accepts flag PREG_OFFSET_CAPTURE (returned in bytes).
	 */
	public static function match(string $subject, string $pattern, int $flags = 0, int $offset = 0): ?array
	{
		if ($offset > strlen($subject)) {
			return null;
		}
		return self::pcre('preg_match', [$pattern, $subject, &$m, $flags, $offset])
			? $m
			: null;
	}


	/**
	 * Performs a global regular expression match. Accepts flag PREG_OFFSET_CAPTURE (returned in bytes), PREG_SET_ORDER is default.
	 */
	public static function matchAll(string $subject, string $pattern, int $flags = 0, int $offset = 0): array
	{
		if ($offset > strlen($subject)) {
			return [];
		}
		self::pcre('preg_match_all', [
			$pattern, $subject, &$m,
			($flags & PREG_PATTERN_ORDER) ? $flags : ($flags | PREG_SET_ORDER),
			$offset,
		]);
		return $m;
	}


	/**
	 * Perform a regular expression search and replace.
	 * @param  string|array  $pattern
	 * @param  string|callable  $replacement
	 */
	public static function replace(string $subject, $pattern, $replacement = null, int $limit = -1): string
	{
		if (is_object($replacement) || is_array($replacement)) {
			if (!is_callable($replacement, false, $textual)) {
				throw new Nette\InvalidStateException("Callback '$textual' is not callable.");
			}
			return self::pcre('preg_replace_callback', [$pattern, $replacement, $subject, $limit]);

		} elseif ($replacement === null && is_array($pattern)) {
			$replacement = array_values($pattern);
			$pattern = array_keys($pattern);
		}

		return self::pcre('preg_replace', [$pattern, $replacement, $subject, $limit]);
	}


	/** @internal */
	public static function pcre(string $func, array $args)
	{
		$res = Callback::invokeSafe($func, $args, function (string $message) use ($args): void {
			// compile-time error, not detectable by preg_last_error
			throw new RegexpException($message . ' in pattern: ' . implode(' or ', (array) $args[0]));
		});

		if (($code = preg_last_error()) // run-time error, but preg_last_error & return code are liars
			&& ($res === null || !in_array($func, ['preg_filter', 'preg_replace_callback', 'preg_replace'], true))
		) {
			throw new RegexpException((RegexpException::MESSAGES[$code] ?? 'Unknown error')
				. ' (pattern: ' . implode(' or ', (array) $args[0]) . ')', $code);
		}
		return $res;
	}
}
