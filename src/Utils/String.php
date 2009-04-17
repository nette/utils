<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id$
 */

/*namespace Nette;*/



/**
 * String tools library.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
final class String
{

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
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
	 * @param  string
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
	 * @param  string  in UTF-8
	 * @param  string
	 * @return string
	 */
	public static function webalize($s, $charlist = NULL)
	{
		$s = strtr($s, '`\'"^~', '-----');
		if (ICONV_IMPL === 'glibc') {
			$s = iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s);
			$s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2"
				."\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe",
				"ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
		} else {
			$s = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
		}
		$s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
		$s = strtolower($s);
		$s = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#', '-', $s);
		$s = trim($s, '-');
		return $s;
	}



	/**
	 * Truncates string to maximal length.
	 * @param  string  in UTF-8
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function truncate($s, $maxLen, $append = "\xE2\x80\xA6")
	{
		if (iconv_strlen($s, 'UTF-8') > $maxLen) {
			$maxLen = $maxLen - iconv_strlen($append, 'UTF-8');
			if ($maxLen < 1) {
				return $append;

			} elseif (preg_match('#^.{1,'.$maxLen.'}(?=[\s\x00-@\[-`{-~])#us', $s, $matches)) {
				return $matches[0] . $append;

			} else {
				return iconv_substr($s, 0, $maxLen, 'UTF-8') . $append;
			}
		}
		return $s;
	}



	/**
	 * Indents the content from the left.
	 * @param  string
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function indent($s, $level = 1, $chars = "\t")
	{
		return $level < 1 ? $s : preg_replace('#(?:^|[\r\n]+)(?=[^\r\n])#', '$0' . str_repeat($chars, $level), $s);
	}



	/**
	 * Convert to lower case.
	 * @param  string
	 * @return string
	 */
	public static function lower($s)
	{
		return mb_strtolower($s, 'UTF-8');
	}



	/**
	 * Convert to upper case.
	 * @param  string
	 * @return string
	 */
	public static function upper($s)
	{
		return mb_strtoupper($s, 'UTF-8');
	}



	/**
	 * Capitalize string.
	 * @param  string
	 * @return string
	 */
	public static function capitalize($s)
	{
		return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
	}



	/**
	 * @deprecated
	 */
	public static function bytes($bytes)
	{
		trigger_error(__METHOD__ . '() is deprecated; use TemplateHelpers::bytes() instead.', /**/E_USER_WARNING/**//*E_USER_DEPRECATED*/);
		return /*Nette\Templates\*/TemplateHelpers::bytes($bytes);
	}

}