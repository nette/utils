<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

/**
 * Date time format collection.
 */
trait DateTimeFormat
{
	public function getYear(): int
	{
		return (int) $this->format('Y');
	}


	public function getMonth(): int
	{
		return (int) $this->format('n');
	}


	public function getDay(): int
	{
		return (int) $this->format('j');
	}


	public function getHour(): int
	{
		return (int) $this->format('G');
	}


	public function getMinute(): int
	{
		return (int) $this->format('i');
	}


	public function getSecond(): int
	{
		return (int) $this->format('s');
	}


	public function getMicrosecond(): int
	{
		return (int) $this->format('u');
	}


	public function getMillisecond(): int
	{
		return (int) $this->format('v');
	}


	public function getDate(): string
	{
		return $this->format('Y-m-d');
	}


	public function getDateTime(): string
	{
		return $this->format('Y-m-d H:i:s');
	}


	public function getDateTimeTz(): string
	{
		return $this->format('Y-m-d H:i:sO');
	}


	public function getDateTimeMicro(): string
	{
		$micro = rtrim($this->format('u'), '0');
		if ($micro === '') {
			$micro = '0';
		}

		return $this->format('Y-m-d H:i:s.') . $micro;
	}


	public function getDateTimeMicroTz(): string
	{
		return $this->getDateTimeMicro() . $this->format('O');
	}


	public function getTimestampMicro(): float
	{
		return (float) $this->format('U.u');
	}


	public function getDayOfWeek(): int
	{
		return (int) $this->format('N');
	}
}
