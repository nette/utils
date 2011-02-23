<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */



/**
 * DateTime with serialization and timestamp support for PHP 5.2.
 *
 * @author     David Grudl
 * @phpversion < 5.3
 */
class DateTime53 extends DateTime
{

	public static function __set_state($state)
	{
		return new self($state['date'], new DateTimeZone($state['timezone']));
	}



	public function __sleep()
	{
		$this->fix = array($this->format('Y-m-d H:i:s'), $this->getTimezone()->getName());
		return array('fix');
	}



	public function __wakeup()
	{
		$this->__construct($this->fix[0], new DateTimeZone($this->fix[1]));
		unset($this->fix);
	}



	public function getTimestamp()
	{
		return (int) $this->format('U');
	}



	public function setTimestamp($timestamp)
	{
		return $this->__construct(gmdate('Y-m-d H:i:s', $timestamp + $this->getOffset()), new DateTimeZone($this->getTimezone()->getName())); // simply getTimezone() crashes in PHP 5.2.6
	}

}
