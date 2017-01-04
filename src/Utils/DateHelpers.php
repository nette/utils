<?php

namespace Nette\Utils;

use Nette;

/**
 * DateTime manipulation helpers.
 */
class DateHelpers
{
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


	public function __construct()
	{
		throw new Nette\StaticClassException;
	}


	/**
	 * @param string|int|\DateTimeInterface $time
	 * @return \DateTime
	 */
	public static function createMutable($time)
	{
		if ($time instanceof \DateTime) {
			return clone $time;

		} elseif ($time instanceof \DateTimeImmutable) {
			return \DateTime::createFromImmutable($time);

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return (new \DateTime('@' . $time))->setTimeZone(new \DateTimeZone(date_default_timezone_get()));

		} elseif ($time) {
			return new \DateTime($time);

		} else {
			return new \DateTime();
		}
	}


	/**
	 * @param string|int|\DateTimeInterface $time
	 * @return \DateTimeImmutable
	 */
	public static function createImmutable($time)
	{
		if ($time instanceof \DateTimeImmutable) {
			return clone $time;

		} elseif ($time instanceof \Datetime) {
			return \DateTimeImmutable::createFromMutable($time);

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return (new \DateTimeImmutable('@' . $time))->setTimeZone(new \DateTimeZone(date_default_timezone_get()));

		} elseif ($time) {
			return new \DateTimeImmutable($time);

		} else {
			return new \DateTimeImmutable();
		}
	}


	/**
	 * Returns new \DateTime object formatted according to the specified format or FALSE on failure.
	 * @param string The format the $time parameter should be in
	 * @param string String representing the time
	 * @param string|\DateTimeZone desired timezone (default timezone is used if NULL is passed)
	 * @return \DateTime|FALSE
	 */
	public static function createMutableFromFormat($format, $time, $timezone = NULL)
	{
		$timezone = self::checkTimezone($timezone);
		return \DateTime::createFromFormat($format, $time, $timezone);
	}


	/**
	 * Returns new \DateTimeImmutable object formatted according to the specified format or FALSE on failure.
	 * @param string The format the $time parameter should be in
	 * @param string String representing the time
	 * @param string|\DateTimeZone desired timezone (default timezone is used if NULL is passed)
	 * @return \DateTimeImmutable|FALSE
	 */
	public static function createImmutableFromFormat($format, $time, $timezone = NULL)
	{
		$timezone = self::checkTimezone($timezone);
		return \DateTimeImmutable::createFromFormat($format, $time, $timezone);
	}


	/**
	 * @param string
	 * @return \DateTimeZone
	 */
	private static function checkTimezone($timezone = NULL)
	{
		if ($timezone === NULL) {
			return new \DateTimeZone(date_default_timezone_get());

		} elseif (is_string($timezone)) {
			return new \DateTimeZone($timezone);

		} elseif (!$timezone instanceof \DateTimeZone) {
			throw new Nette\InvalidArgumentException('Invalid timezone given');
		}
	}


	/**
	 * @param \DateTimeInterface
	 * @param string
	 * @return \DateTimeInterface
	 */
	public static function modifyClone(\DateTimeInterface $dateTime, $modify = '')
	{
		if ($dateTime instanceof \DateTimeImmutable) {
			return $modify ? $dateTime->modify($modify) : clone $dateTime;
		}

		$dateTime = clone $dateTime;
		return $modify ? $dateTime->modify($modify) : $dateTime;
	}


	/**
	 * @param  \DateTimeInterface
	 * @param  int
	 * @return \DateTimeInterface
	 */
	public static function setTimestamp(\DateTimeInterface $dateTime, $timestamp)
	{
		$zone = $dateTime->getTimezone();

		if ($dateTime instanceof \DateTimeImmutable) {
			return (new \DateTimeImmutable('@' . $timestamp))->setTimezone($zone);
		}

		$dateTime->__construct('@' . $timestamp);
		return $dateTime->setTimeZone($zone);
	}


	/**
	 * @param \DateTimeInterface
	 * @return int|string
	 */
	public static function getTimestamp(\DateTimeInterface $dateTime)
	{
		$ts = $dateTime->format('U');
		return is_float($tmp = $ts * 1) ? $ts : $tmp;
	}
}
