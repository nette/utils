<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;


class Helpers
{
	/**
	 * Captures PHP output into a string.
	 */
	public static function capture(callable $func): string
	{
		ob_start(function () {});
		try {
			$func();
			return ob_get_clean();
		} catch (\Throwable $e) {
			ob_end_clean();
			throw $e;
		}
	}


	/**
	 * Converts false to null.
	 */
	public static function falseToNull($val)
	{
		return $val === false ? null : $val;
	}
}
