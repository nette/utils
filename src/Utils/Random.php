<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * Secure random string generator.
 *
 * @author     David Grudl
 */
class Random
{

	/**
	 * Generate random string.
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function generate($length = 10, $charlist = '0-9a-z')
	{
		$charlist = str_shuffle(preg_replace_callback('#.-.#', function($m) {
			return implode('', range($m[0][0], $m[0][2]));
		}, $charlist));
		$chLen = strlen($charlist);

		if (function_exists('openssl_random_pseudo_bytes')
			&& (PHP_VERSION_ID >= 50400 || !defined('PHP_WINDOWS_VERSION_BUILD')) // slow in PHP 5.3 & Windows
		) {
			$rand3 = openssl_random_pseudo_bytes($length);
		}
		if (empty($rand3) && function_exists('mcrypt_create_iv') && (PHP_VERSION_ID !== 50303 || !defined('PHP_WINDOWS_VERSION_BUILD'))) { // PHP bug #52523
			$rand3 = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
		}
		if (empty($rand3) && @is_readable('/dev/urandom')) {
			$rand3 = file_get_contents('/dev/urandom', FALSE, NULL, -1, $length);
		}
		if (empty($rand3)) {
			static $cache;
			$rand3 = $cache ?: $cache = md5(serialize($_SERVER), TRUE);
		}

		$s = '';
		for ($i = 0; $i < $length; $i++) {
			if ($i % 5 === 0) {
				list($rand, $rand2) = explode(' ', microtime());
				$rand += lcg_value();
			}
			$rand *= $chLen;
			$s .= $charlist[($rand + $rand2 + ord($rand3[$i % strlen($rand3)])) % $chLen];
			$rand -= (int) $rand;
		}
		return $s;
	}

}
