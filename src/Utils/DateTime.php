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
class DateTime extends \DateTime implements \JsonSerializable
{
	use Nette\SmartObject;

	/** minute in seconds */
	const MINUTE = 60;

	/** hour in seconds */
	const HOUR = 60 * self::MINUTE;

	/** day in seconds */
	const DAY = 24 * self::HOUR;

	/** week in seconds */
	const WEEK = 7 * self::DAY;

	/** average month in seconds */
	const MONTH = 2629800;

	/** average year in seconds */
	const YEAR = 31557600;


	/**
	 * DateTime object factory.
	 * @param  string|int|\DateTimeInterface $time
	 * @return static
	 */
	public static function from($time)
	{
		if ($time instanceof \DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s.u'), $time->getTimezone());

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return (new static('@' . $time))->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		} else { // textual or null
			return new static($time);
		}
	}


	/**
	 * Creates DateTime object.
	 * @return static
	 */
	public static function fromParts(int $year, int $month, int $day, int $hour = 0, int $minute = 0, float $second = 0)
	{
		$s = sprintf('%04d-%02d-%02d %02d:%02d:%02.5f', $year, $month, $day, $hour, $minute, $second);
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
		$dolly = clone $this;
		return $modify ? $dolly->modify($modify) : $dolly;
	}


	/**
	 * @param  int|string $timestamp
	 * @return static
	 */
	public function setTimestamp($timestamp)
	{
		$zone = $this->getTimezone();
		$this->__construct('@' . $timestamp);
		return $this->setTimezone($zone);
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
	 * @param  string               $format   The format the $time parameter should be in
	 * @param  string               $time     String representing the time
	 * @param  string|\DateTimeZone $timezone desired timezone (default timezone is used if null is passed)
	 * @return static|false
	 */
	public static function createFromFormat($format, $time, $timezone = null)
	{
		if ($timezone === null) {
			$timezone = new \DateTimeZone(date_default_timezone_get());

		} elseif (is_string($timezone)) {
			$timezone = new \DateTimeZone($timezone);

		} elseif (!$timezone instanceof \DateTimeZone) {
			throw new Nette\InvalidArgumentException('Invalid timezone given');
		}

		$date = parent::createFromFormat($format, $time, $timezone);
		return $date ? static::from($date) : false;
	}


	/**
	 * Returns JSON representation in ISO 8601 (used by JavaScript).
	 */
	public function jsonSerialize(): string
	{
		return $this->format('c');
	}
}
