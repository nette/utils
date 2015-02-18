<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * String tools library.
 *
 * @author     David Grudl
 */
class Strings
{

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new Nette\StaticClassException;
	}


	/**
	 * Checks if the string is valid for UTF-8 encoding.
	 * @param  string  byte stream to check
	 * @return bool
	 */
	public static function checkEncoding($s)
	{
		return $s === self::fixEncoding($s);
	}


	/**
	 * Removes invalid code unit sequences from UTF-8 string.
	 * @param  string  byte stream to fix
	 * @return string
	 */
	public static function fixEncoding($s)
	{
		// removes xD800-xDFFF, x110000 and higher
		if (PHP_VERSION_ID < 50400) {
			return @iconv('UTF-16', 'UTF-8//IGNORE', iconv('UTF-8', 'UTF-16//IGNORE', $s)); // intentionally @
		} else {
			return htmlspecialchars_decode(htmlspecialchars($s, ENT_NOQUOTES | ENT_IGNORE, 'UTF-8'), ENT_NOQUOTES);
		}
	}


	/**
	 * Returns a specific character in UTF-8.
	 * @param  int     code point (0x0 to 0xD7FF or 0xE000 to 0x10FFFF)
	 * @return string
	 * @throws Nette\InvalidArgumentException if code point is not in valid range
	 */
	public static function chr($code)
	{
		if ($code < 0 || ($code >= 0xD800 && $code <= 0xDFFF) || $code > 0x10FFFF) {
			throw new Nette\InvalidArgumentException('Code point must be in range 0x0 to 0xD7FF or 0xE000 to 0x10FFFF.');
		}
		return iconv('UTF-32BE', 'UTF-8//IGNORE', pack('N', $code));
	}


	/**
	 * Starts the $haystack string with the prefix $needle?
	 * @param  string
	 * @param  string
	 * @return bool
	 */
	public static function startsWith($haystack, $needle)
	{
		return strncmp($haystack, $needle, strlen($needle)) === 0;
	}


	/**
	 * Ends the $haystack string with the suffix $needle?
	 * @param  string
	 * @param  string
	 * @return bool
	 */
	public static function endsWith($haystack, $needle)
	{
		return strlen($needle) === 0 || substr($haystack, -strlen($needle)) === $needle;
	}


	/**
	 * Does $haystack contain $needle?
	 * @param  string
	 * @param  string
	 * @return bool
	 */
	public static function contains($haystack, $needle)
	{
		return strpos($haystack, $needle) !== FALSE;
	}


	/**
	 * Returns a part of UTF-8 string.
	 * @param  string
	 * @param  int in characters (code points)
	 * @param  int in characters (code points)
	 * @return string
	 */
	public static function substring($s, $start, $length = NULL)
	{
		if (function_exists('mb_substr')) {
			if ($length === NULL && PHP_VERSION_ID < 50408) {
				$length = self::length($s);
			}
			return mb_substr($s, $start, $length, 'UTF-8'); // MB is much faster
		} elseif ($length === NULL) {
			$length = self::length($s);
		} elseif ($start < 0 && $length < 0) {
			$start += self::length($s); // unifies iconv_substr behavior with mb_substr
		}
		return iconv_substr($s, $start, $length, 'UTF-8');
	}


	/**
	 * Removes special controls characters and normalizes line endings and spaces.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function normalize($s)
	{
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
	 * @param  string  UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function normalizeNewLines($s)
	{
		return str_replace(array("\r\n", "\r"), "\n", $s);
	}


	/**
	 * Converts to ASCII.
	 * @param  string  UTF-8 encoding
	 * @return string  ASCII
	 */
	public static function toAscii($s)
	{
		$s = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{2FF}\x{370}-\x{10FFFF}]#u', '', $s);
		$s = strtr($s, '`\'"^~?', "\x01\x02\x03\x04\x05\x06");
		$s = str_replace(
			array("\xE2\x80\x9E", "\xE2\x80\x9C", "\xE2\x80\x9D", "\xE2\x80\x9A", "\xE2\x80\x98", "\xE2\x80\x99", "\xC2\xB0"),
			array("\x03", "\x03", "\x03", "\x02", "\x02", "\x02", "\x04"), $s
		);
		if (class_exists('Transliterator') && $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII')) {
			$s = $transliterator->transliterate($s);
		}
		if (ICONV_IMPL === 'glibc') {
			$s = str_replace(
				array("\xC2\xBB", "\xC2\xAB", "\xE2\x80\xA6", "\xE2\x84\xA2", "\xC2\xA9", "\xC2\xAE"),
				array('>>', '<<', '...', 'TM', '(c)', '(R)'), $s
			);
			$s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s); // intentionally @
			$s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
				. "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
				. "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
				. "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe"
				. "\x96\xa0\x8b\x97\x9b\xa6\xad\xb7",
				"ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt- <->|-.");
			$s = preg_replace('#[^\x00-\x7F]++#', '', $s);
		} else {
			$s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
		}
		$s = str_replace(array('`', "'", '"', '^', '~', '?'), '', $s);
		return strtr($s, "\x01\x02\x03\x04\x05\x06", '`\'"^~?');
	}


	/**
	 * Converts to web safe characters [a-z0-9-] text.
	 * @param  string  UTF-8 encoding
	 * @param  string  allowed characters
	 * @param  bool
	 * @return string
	 */
	public static function webalize($s, $charlist = NULL, $lower = TRUE)
	{
		$s = self::toAscii($s);
		if ($lower) {
			$s = strtolower($s);
		}
		$s = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $s);
		$s = trim($s, '-');
		return $s;
	}


	/**
	 * Truncates string to maximal length.
	 * @param  string  UTF-8 encoding
	 * @param  int
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function truncate($s, $maxLen, $append = "\xE2\x80\xA6")
	{
		if (self::length($s) > $maxLen) {
			$maxLen = $maxLen - self::length($append);
			if ($maxLen < 1) {
				return $append;

			} elseif ($matches = self::match($s, '#^.{1,'.$maxLen.'}(?=[\s\x00-/:-@\[-`{-~])#us')) {
				return $matches[0] . $append;

			} else {
				return self::substring($s, 0, $maxLen) . $append;
			}
		}
		return $s;
	}


	/**
	 * Indents the content from the left.
	 * @param  string  UTF-8 encoding or 8-bit
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function indent($s, $level = 1, $chars = "\t")
	{
		if ($level > 0) {
			$s = self::replace($s, '#(?:^|[\r\n]+)(?=[^\r\n])#', '$0' . str_repeat($chars, $level));
		}
		return $s;
	}


	/**
	 * Convert to lower case.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function lower($s)
	{
		return mb_strtolower($s, 'UTF-8');
	}


	/**
	 * Convert first character to lower case.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function firstLower($s)
	{
		return self::lower(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Convert to upper case.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function upper($s)
	{
		return mb_strtoupper($s, 'UTF-8');
	}


	/**
	 * Convert first character to upper case.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function firstUpper($s)
	{
		return self::upper(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Capitalize string.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function capitalize($s)
	{
		return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
	}


	/**
	 * Case-insensitive compares UTF-8 strings.
	 * @param  string
	 * @param  string
	 * @param  int
	 * @return bool
	 */
	public static function compare($left, $right, $len = NULL)
	{
		if ($len < 0) {
			$left = self::substring($left, $len, -$len);
			$right = self::substring($right, $len, -$len);
		} elseif ($len !== NULL) {
			$left = self::substring($left, 0, $len);
			$right = self::substring($right, 0, $len);
		}
		return self::lower($left) === self::lower($right);
	}


	/**
	 * Finds the length of common prefix of strings.
	 * @param  string|array
	 * @return string
	 */
	public static function findPrefix($strings)
	{
		if (!is_array($strings)) {
			$strings = func_get_args();
		}
		$first = array_shift($strings);
		for ($i = 0; $i < strlen($first); $i++) {
			foreach ($strings as $s) {
				if (!isset($s[$i]) || $first[$i] !== $s[$i]) {
					while ($i && $first[$i-1] >= "\x80" && $first[$i] >= "\x80" && $first[$i] < "\xC0") {
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
	 * @param  string
	 * @return int
	 */
	public static function length($s)
	{
		return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen(utf8_decode($s));
	}


	/**
	 * Strips whitespace.
	 * @param  string  UTF-8 encoding
	 * @param  string
	 * @return string
	 */
	public static function trim($s, $charlist = " \t\n\r\0\x0B\xC2\xA0")
	{
		$charlist = preg_quote($charlist, '#');
		return self::replace($s, '#^['.$charlist.']+|['.$charlist.']+\z#u', '');
	}


	/**
	 * Pad a string to a certain length with another string.
	 * @param  string  UTF-8 encoding
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function padLeft($s, $length, $pad = ' ')
	{
		$length = max(0, $length - self::length($s));
		$padLen = self::length($pad);
		return str_repeat($pad, $length / $padLen) . self::substring($pad, 0, $length % $padLen) . $s;
	}


	/**
	 * Pad a string to a certain length with another string.
	 * @param  string  UTF-8 encoding
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function padRight($s, $length, $pad = ' ')
	{
		$length = max(0, $length - self::length($s));
		$padLen = self::length($pad);
		return $s . str_repeat($pad, $length / $padLen) . self::substring($pad, 0, $length % $padLen);
	}


	/**
	 * Reverse string.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function reverse($s)
	{
		return @iconv('UTF-32LE', 'UTF-8', strrev(@iconv('UTF-8', 'UTF-32BE', $s)));
	}


	/**
	 * Use Nette\Utils\Random::generate
	 * @deprecated
	 */
	public static function random($length = 10, $charlist = '0-9a-z')
	{
		return Random::generate($length, $charlist);
	}


	/**
	 * Splits string by a regular expression.
	 * @param  string
	 * @param  string
	 * @param  int
	 * @return array
	 */
	public static function split($subject, $pattern, $flags = 0)
	{
		return self::pcre('preg_split', array($pattern, $subject, -1, $flags | PREG_SPLIT_DELIM_CAPTURE));
	}


	/**
	 * Performs a regular expression match.
	 * @param  string
	 * @param  string
	 * @param  int  can be PREG_OFFSET_CAPTURE (returned in bytes)
	 * @param  int  offset in bytes
	 * @return mixed
	 */
	public static function match($subject, $pattern, $flags = 0, $offset = 0)
	{
		if ($offset > strlen($subject)) {
			return NULL;
		}
		return self::pcre('preg_match', array($pattern, $subject, & $m, $flags, $offset))
			? $m
			: NULL;
	}


	/**
	 * Performs a global regular expression match.
	 * @param  string
	 * @param  string
	 * @param  int  can be PREG_OFFSET_CAPTURE (returned in bytes); PREG_SET_ORDER is default
	 * @param  int  offset in bytes
	 * @return array
	 */
	public static function matchAll($subject, $pattern, $flags = 0, $offset = 0)
	{
		if ($offset > strlen($subject)) {
			return array();
		}
		self::pcre('preg_match_all', array(
			$pattern, $subject, & $m,
			($flags & PREG_PATTERN_ORDER) ? $flags : ($flags | PREG_SET_ORDER),
			$offset
		));
		return $m;
	}


	/**
	 * Perform a regular expression search and replace.
	 * @param  string
	 * @param  string|array
	 * @param  string|callable
	 * @param  int
	 * @return string
	 */
	public static function replace($subject, $pattern, $replacement = NULL, $limit = -1)
	{
		if (is_object($replacement) || is_array($replacement)) {
			if ($replacement instanceof Nette\Callback) {
				$replacement = $replacement->getNative();
			}
			if (!is_callable($replacement, FALSE, $textual)) {
				throw new Nette\InvalidStateException("Callback '$textual' is not callable.");
			}

			return self::pcre('preg_replace_callback', array($pattern, $replacement, $subject, $limit));

		} elseif ($replacement === NULL && is_array($pattern)) {
			$replacement = array_values($pattern);
			$pattern = array_keys($pattern);
		}

		return self::pcre('preg_replace', array($pattern, $replacement, $subject, $limit));
	}


	/** @internal */
	public static function pcre($func, $args)
	{
		static $messages = array(
			PREG_INTERNAL_ERROR => 'Internal error',
			PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
			PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
			PREG_BAD_UTF8_ERROR => 'Malformed UTF-8 data',
			5 => 'Offset didn\'t correspond to the begin of a valid UTF-8 code point', // PREG_BAD_UTF8_OFFSET_ERROR
		);
		$res = Callback::invokeSafe($func, $args, function($message) use ($args) {
			// compile-time error, not detectable by preg_last_error
			throw new RegexpException($message . ' in pattern: ' . implode(' or ', (array) $args[0]));
		});

		if (($code = preg_last_error()) // run-time error, but preg_last_error & return code are liars
			&& ($res === NULL || !in_array($func, array('preg_filter', 'preg_replace_callback', 'preg_replace')))
		) {
			throw new RegexpException((isset($messages[$code]) ? $messages[$code] : 'Unknown error')
				. ' (pattern: ' . implode(' or ', (array) $args[0]) . ')', $code);
		}
		return $res;
	}

}


/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception
{
}
