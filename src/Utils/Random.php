<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;


/**
 * Secure random string generator.
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
		$charlist = count_chars(preg_replace_callback('#.-.#', function ($m) {
			return implode('', range($m[0][0], $m[0][2]));
		}, $charlist), 3);
		$chLen = strlen($charlist);

		if ($length < 1) {
			return ''; // mcrypt_create_iv does not support zero length
		} elseif ($chLen < 2) {
			return str_repeat($charlist, $length); // random_int does not support empty interval
		}

		$res = '';
		if (PHP_VERSION_ID >= 70000) {
			for ($i = 0; $i < $length; $i++) {
				$res .= $charlist[random_int(0, $chLen - 1)];
			}
			return $res;
		}

		$windows = defined('PHP_WINDOWS_VERSION_BUILD');
		$bytes = '';
		if (function_exists('openssl_random_pseudo_bytes')
			&& (PHP_VERSION_ID >= 50400 || !defined('PHP_WINDOWS_VERSION_BUILD')) // slow in PHP 5.3 & Windows
		) {
			$bytes = openssl_random_pseudo_bytes($length, $secure);
			if (!$secure) {
				$bytes = '';
			}
		}
		if (strlen($bytes) < $length && function_exists('mcrypt_create_iv') && (PHP_VERSION_ID >= 50307 || !$windows)) { // PHP bug #52523
			$bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
		}
		if (strlen($bytes) < $length && !$windows && @is_readable('/dev/urandom')) {
			$bytes = file_get_contents('/dev/urandom', FALSE, NULL, -1, $length);
		}
		if (strlen($bytes) < $length) {
			$rand3 = md5(serialize($_SERVER), TRUE);
			$charlist = str_shuffle($charlist);
			for ($i = 0; $i < $length; $i++) {
				if ($i % 5 === 0) {
					list($rand1, $rand2) = explode(' ', microtime());
					$rand1 += lcg_value();
				}
				$rand1 *= $chLen;
				$res .= $charlist[($rand1 + $rand2 + ord($rand3[$i % strlen($rand3)])) % $chLen];
				$rand1 -= (int) $rand1;
			}
			return $res;
		}

		for ($i = 0; $i < $length; $i++) {
			$res .= $charlist[($i + ord($bytes[$i])) % $chLen];
		}
		return $res;
	}

}
