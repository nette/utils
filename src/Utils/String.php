<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette
 */

namespace Nette;

use Nette;



/**
 * String tools library.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
final class String
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
	 * Converts to web safe characters [a-z0-9-] text.
	 * @param  string  UTF-8 encoding
	 * @param  string  ASCII
	 * @param  bool
	 * @return string
	 */
	public static function webalize($s, $charlist = NULL, $lower = TRUE)
	{
		$s = strtr($s, '`\'"^~', '-----');
		if (ICONV_IMPL === 'glibc') {
			setlocale(LC_CTYPE, 'en_US.UTF-8');
		}
		$s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
		$s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
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

			} elseif ($matches = self::match($s, '#^.{1,'.$maxLen.'}(?=[\s\x00-@\[-`{-~])#us')) {
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
	 * Splits string by a regular expression.
	 * @param  string
	 * @param  string
	 * @param  int
	 * @return array
	 */
	public static function split($subject, $pattern, $flags = 0)
	{
		$res = preg_split($pattern, $subject, -1, $flags | PREG_SPLIT_DELIM_CAPTURE);
		self::checkPreg($res, $pattern);
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
		$res = preg_match($pattern, $subject, $m, $flags, $offset);
		self::checkPreg($res, $pattern);
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
		$res = preg_match_all($pattern, $subject, $m, ($flags & PREG_PATTERN_ORDER) ? $flags : ($flags | PREG_SET_ORDER), $offset);
		self::checkPreg($res, $pattern);
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
		preg_match('##', '');
		if (is_object($replacement) || is_array($replacement)) {
			/*5.2*if ($replacement instanceof Callback) {
				$replacement = $replacement->getNative();
			}*/
			if (!is_callable($replacement, FALSE, $textual)) {
				throw new \InvalidStateException("Callback '$textual' is not callable.");
			}
			$res = preg_replace_callback($pattern, $replacement, $subject, $limit);

		} elseif (is_array($pattern)) {
			$res = preg_replace(array_keys($pattern), array_values($pattern), $subject, $limit);

		} else {
			$res = preg_replace($pattern, $replacement, $subject, $limit);
		}

		if (preg_last_error()) {
			self::checkPreg(TRUE, $pattern);
		} elseif ($res === NULL) {
			self::checkPreg(FALSE, $pattern);
		} else {
			return $res;
		}
	}



	private static function checkPreg($res, $pattern)
	{
		if ($res === FALSE) { // compile error
			$error = error_get_last();
			throw new RegexpException("$error[message] in pattern: $pattern");

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
 * @package    Nette
 */
class RegexpException extends \Exception
{
}
