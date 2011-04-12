<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette;

use Nette;



/**
 * String tools library.
 *
 * @author     David Grudl
 */
class StringUtils
{

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new \LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Checks if the string is valid for the specified encoding.
	 * @param  string  byte stream to check
	 * @param  string  expected encoding
	 * @return bool
	 */
	public static function checkEncoding($s, $encoding = 'UTF-8')
	{
		return $s === self::fixEncoding($s, $encoding);
	}



	/**
	 * Returns correctly encoded string.
	 * @param  string  byte stream to fix
	 * @param  string  encoding
	 * @return string
	 */
	public static function fixEncoding($s, $encoding = 'UTF-8')
	{
		// removes xD800-xDFFF, xFEFF, xFFFF, x110000 and higher
		return @iconv('UTF-16', $encoding . '//IGNORE', iconv($encoding, 'UTF-16//IGNORE', $s)); // intentionally @
	}



	/**
	 * Returns a specific character.
	 * @param  int     codepoint
	 * @param  string  encoding
	 * @return string
	 */
	public static function chr($code, $encoding = 'UTF-8')
	{
		return iconv('UTF-32BE', $encoding . '//IGNORE', pack('N', $code));
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
	 * Removes special controls characters and normalizes line endings and spaces.
	 * @param  string  UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function normalize($s)
	{
		// standardize line endings to unix-like
		$s = str_replace("\r\n", "\n", $s); // DOS
		$s = strtr($s, "\r", "\n"); // Mac

		// remove control characters; leave \t + \n
		$s = preg_replace('#[\x00-\x08\x0B-\x1F]+#', '', $s);

		// right trim
		$s = preg_replace("#[\t ]+$#m", '', $s);

		// trailing spaces
		$s = trim($s, "\n");

		return $s;
	}



	/**
	 * Converts to ASCII.
	 * @param  string  UTF-8 encoding
	 * @return string  ASCII
	 */
	public static function toAscii($s)
	{
		$s = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u', '', $s);
		$s = strtr($s, '`\'"^~', "\x01\x02\x03\x04\x05");
		if (ICONV_IMPL === 'glibc') {
			$s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s); // intentionally @
			$s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
				. "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
				. "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
				. "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
				"ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
		} else {
			$s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
		}
		$s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
		return strtr($s, "\x01\x02\x03\x04\x05", '`\'"^~');
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
		if ($lower) $s = strtolower($s);
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
				return iconv_substr($s, 0, $maxLen, 'UTF-8') . $append;
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
		return $level < 1 ? $s : self::replace($s, '#(?:^|[\r\n]+)(?=[^\r\n])#', '$0' . str_repeat($chars, $level));
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
		return self::upper(mb_substr($s, 0, 1, 'UTF-8')) . mb_substr($s, 1, self::length($s), 'UTF-8');
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
			$left = iconv_substr($left, $len, -$len, 'UTF-8');
			$right = iconv_substr($right, $len, -$len, 'UTF-8');
		} elseif ($len !== NULL) {
			$left = iconv_substr($left, 0, $len, 'UTF-8');
			$right = iconv_substr($right, 0, $len, 'UTF-8');
		}
		return self::lower($left) === self::lower($right);
	}



	/**
	 * Returns UTF-8 string length.
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
		return self::replace($s, '#^['.$charlist.']+|['.$charlist.']+$#u', '');
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
		return str_repeat($pad, $length / $padLen) . iconv_substr($pad, 0, $length % $padLen, 'UTF-8') . $s;
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
		return $s . str_repeat($pad, $length / $padLen) . iconv_substr($pad, 0, $length % $padLen, 'UTF-8');
	}



	/**
	 * Generate random string.
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function random($length = 10, $charlist = '0-9a-z')
	{
		$charlist = str_shuffle(preg_replace_callback('#.-.#', function($m) {
			return implode('', range($m[0][0], $m[0][2]));
		}, $charlist));
		$chLen = strlen($charlist);

		$s = '';
		for ($i = 0; $i < $length; $i++) {
			if ($i % 5 === 0) {
				$rand = lcg_value();
				$rand2 = microtime(TRUE);
			}
			$rand *= $chLen;
			$s .= $charlist[($rand + $rand2) % $chLen];
			$rand -= (int) $rand;
		}
		return $s;
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
		Diagnostics\Debugger::tryError();
		$res = preg_split($pattern, $subject, -1, $flags | PREG_SPLIT_DELIM_CAPTURE);
		self::catchPregError($pattern);
		return $res;
	}



	/**
	 * Performs a regular expression match.
	 * @param  string
	 * @param  string
	 * @param  int
	 * @param  int
	 * @return mixed
	 */
	public static function match($subject, $pattern, $flags = 0, $offset = 0)
	{
		Diagnostics\Debugger::tryError();
		$res = preg_match($pattern, $subject, $m, $flags, $offset);
		self::catchPregError($pattern);
		if ($res) {
			return $m;
		}
	}



	/**
	 * Performs a global regular expression match.
	 * @param  string
	 * @param  string
	 * @param  int  (PREG_SET_ORDER is default)
	 * @param  int
	 * @return array
	 */
	public static function matchAll($subject, $pattern, $flags = 0, $offset = 0)
	{
		Diagnostics\Debugger::tryError();
		$res = preg_match_all(
			$pattern, $subject, $m,
			($flags & PREG_PATTERN_ORDER) ? $flags : ($flags | PREG_SET_ORDER),
			$offset
		);
		self::catchPregError($pattern);
		return $m;
	}



	/**
	 * Perform a regular expression search and replace.
	 * @param  string
	 * @param  string|array
	 * @param  string|callback
	 * @param  int
	 * @return string
	 */
	public static function replace($subject, $pattern, $replacement = NULL, $limit = -1)
	{
		Diagnostics\Debugger::tryError();
		if (is_object($replacement) || is_array($replacement)) {
			if ($replacement instanceof Callback) {
				$replacement = $replacement->getNative();
			}
			if (!is_callable($replacement, FALSE, $textual)) {
				Diagnostics\Debugger::catchError($foo);
				throw new InvalidStateException("Callback '$textual' is not callable.");
			}
			$res = preg_replace_callback($pattern, $replacement, $subject, $limit);

			if (Diagnostics\Debugger::catchError($e)) { // compile error
				$trace = $e->getTrace();
				if (isset($trace[2]['class']) && $trace[2]['class'] === __CLASS__) {
					throw new RegexpException($e->getMessage() . " in pattern: $pattern");
				}
			}

		} elseif (is_array($pattern)) {
			$res = preg_replace(array_keys($pattern), array_values($pattern), $subject, $limit);

		} else {
			$res = preg_replace($pattern, $replacement, $subject, $limit);
		}
		self::catchPregError($pattern);
		return $res;
	}



	/** @internal */
	public static function catchPregError($pattern)
	{
		if (Diagnostics\Debugger::catchError($e)) { // compile error
			throw new RegexpException($e->getMessage() . " in pattern: $pattern");

		} elseif (preg_last_error()) { // run-time error
			static $messages = array(
				PREG_INTERNAL_ERROR => 'Internal error',
				PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
				PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
				PREG_BAD_UTF8_ERROR => 'Malformed UTF-8 data',
				5 => 'Offset didn\'t correspond to the begin of a valid UTF-8 code point', // PREG_BAD_UTF8_OFFSET_ERROR
			);
			$code = preg_last_error();
			throw new RegexpException((isset($messages[$code]) ? $messages[$code] : 'Unknown error') . " (pattern: $pattern)", $code);
		}
	}

}



/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception
{
}
