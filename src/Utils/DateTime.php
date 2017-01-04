<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Utils;

use DateTimeZone;
use Nette;


/**
 * @deprecated Use Nette\Utils\DateHelpers.
 * DateTime.
 */
class DateTime extends \DateTime implements \JsonSerializable
{
	use Nette\SmartObject;

	/** minute in seconds */
	const MINUTE = DateHelpers::MINUTE;

	/** hour in seconds */
	const HOUR = DateHelpers::HOUR;

	/** day in seconds */
	const DAY = DateHelpers::DAY;

	/** week in seconds */
	const WEEK = DateHelpers::WEEK;

	/** average month in seconds */
	const MONTH = DateHelpers::MONTH;

	/** average year in seconds */
	const YEAR = DateHelpers::YEAR;

	public function __construct($time = 'now', DateTimeZone $timezone = NULL)
	{
		trigger_error('Class ' . __CLASS__ . ' is deprecated, please use ' . DateHelpers::class . ' instead.', E_USER_DEPRECATED);
		parent::__construct($time, $timezone);
	}

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
	public static function fromParts($year, $month, $day, $hour = 0, $minute = 0, $second = 0)
	{
		$s = sprintf("%04d-%02d-%02d %02d:%02d:%02.5f", $year, $month, $day, $hour, $minute, $second);
		if (!checkdate($month, $day, $year) || $hour < 0 || $hour > 23 || $minute < 0 || $minute > 59 || $second < 0 || $second >= 60) {
			throw new Nette\InvalidArgumentException("Invalid date '$s'");
		}
		return new static($s);
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->format('Y-m-d H:i:s');
	}


	/**
	 * @param  string
	 * @return static
	 */
	public function modifyClone($modify = '')
	{
		return DateHelpers::modifyClone($this, $modify);
	}


	/**
	 * @param  int
	 * @return static
	 */
	public function setTimestamp($timestamp)
	{
		return DateHelpers::setTimestamp($this, $timestamp);
	}


	/**
	 * @return int|string
	 */
	public function getTimestamp()
	{
		return DateHelpers::getTimestamp($this);
	}


	/**
	 * Returns new DateTime object formatted according to the specified format.
	 * @param string The format the $time parameter should be in
	 * @param string String representing the time
	 * @param string|\DateTimeZone desired timezone (default timezone is used if NULL is passed)
	 * @return static|FALSE
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
		return $date ? static::from($date) : FALSE;
	}


	/**
	 * Returns JSON representation in ISO 8601 (used by JavaScript).
	 * @return string
	 */
	public function jsonSerialize()
	{
		return $this->format('c');
	}

}
