<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * DateTime.
 */
class DateTime extends \DateTimeImmutable implements \JsonSerializable
{
	use Nette\SmartObject;

	/** minute in seconds */
	const MINUTE = 60;

	/** hour in seconds */
	const HOUR = 60 * self::MINUTE;

	/** day in seconds */
	const DAY = 24 * self::HOUR;

	/** @deprecated */
	const WEEK = 7 * self::DAY;

	/** @deprecated */
	const MONTH = 2629800;

	/** @deprecated */
	const YEAR = 31557600;


	/**
	 * DateTime object factory.
	 * @param  string|int|\DateTimeInterface
	 * @return static
	 */
	public static function from($time)
	{
		if ($time instanceof \DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s.u'), $time->getTimezone());

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				trigger_error(__METHOD__ . '() and relative timestamp is deprecated.', E_USER_DEPRECATED);
				$time += time();
			}
			return (new static('@' . $time))->setTimeZone(new \DateTimeZone(date_default_timezone_get()));

		} else { // textual or NULL
			return new static($time);
		}
	}


	/**
	 * Creates DateTime object.
	 * @return static
	 */
	public static function fromParts(int $year, int $month, int $day, int $hour = 0, int $minute = 0, float $second = 0)
	{
		$s = sprintf("%04d-%02d-%02d %02d:%02d:%02.5f", $year, $month, $day, $hour, $minute, $second);
		if (!checkdate($month, $day, $year) || $hour < 0 || $hour > 23 || $minute < 0 || $minute > 59 || $second < 0 || $second >= 60) {
			throw new Nette\InvalidArgumentException("Invalid date '$s'");
		}
		return new static($s);
	}


	public function __toString(): string
	{
		return $this->format('Y-m-d H:i:s');
	}


	/**
	 * @return static
	 */
	public function modifyClone(string $modify = '')
	{
		trigger_error(__METHOD__ . '() is deprecated, use modify()', E_USER_DEPRECATED);
		$dolly = clone $this;
		return $modify ? $dolly->modify($modify) : $dolly;
	}


	/**
	 * @param  int|string
	 * @return static
	 */
	public function setTimestamp($timestamp)
	{
		$zone = $this->getTimezone();
		$this->__construct('@' . $timestamp);
		return $this->setTimeZone($zone);
	}


	/**
	 * @return int|string
	 */
	public function getTimestamp()
	{
		$ts = $this->format('U');
		return is_float($tmp = $ts * 1) ? $ts : $tmp;
	}


	/**
	 * Returns new DateTime object formatted according to the specified format.
	 * @param  string The format the $time parameter should be in
	 * @param  string String representing the time
	 * @param  string|\DateTimeZone desired timezone (default timezone is used if NULL is passed)
	 * @return static|NULL
	 */
	public static function createFromFormat($format, $time, $timezone = NULL)
	{
		if ($timezone === NULL) {
			$timezone = new \DateTimeZone(date_default_timezone_get());

		} elseif (is_string($timezone)) {
			$timezone = new \DateTimeZone($timezone);

		} elseif (!$timezone instanceof \DateTimeZone) {
			throw new Nette\InvalidArgumentException('Invalid timezone given');
		}

		$date = parent::createFromFormat($format, $time, $timezone);
		return $date ? static::from($date) : NULL;
	}


	/**
	 * Returns JSON representation in ISO 8601 (used by JavaScript).
	 */
	public function jsonSerialize(): string
	{
		return $this->format('c');
	}


	/********************* immutable usage detector ****************d*g**/


	public function __destruct()
	{
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		if (isset($trace[0]['file'], $trace[1]['function']) && $trace[0]['file'] === __FILE__ && $trace[1]['function'] !== 'from') {
			trigger_error(__CLASS__ . ' is immutable now, check how it is used in ' . $trace[1]['file'] . ':' . $trace[1]['line'], E_USER_WARNING);
		}
	}


	public function add($interval)
	{
		return parent::add($interval);
	}


	public function modify($modify)
	{
		return parent::modify($modify);
	}


	public function setDate($year, $month, $day)
	{
		return parent::setDate($year, $month, $day);
	}


	public function setISODate($year, $week, $day = 1)
	{
		return parent::setISODate($year, $week, $day);
	}


	public function setTime($hour, $minute, $second = 0, $micro = 0)
	{
		return PHP_VERSION_ID < 70100
			? parent::setTime($hour, $minute, $second)
			: parent::setTime($hour, $minute, $second, $micro);
	}


	public function setTimezone($timezone)
	{
		return parent::setTimezone($timezone);
	}


	public function sub($interval)
	{
		return parent::sub($interval);
	}

}
