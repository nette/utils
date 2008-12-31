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

// no namespace



/*
some useful SPL exception:

- LogicException
	- InvalidArgumentException
	- LengthException
- RuntimeException
	- OutOfBoundsException
	- UnexpectedValueException

other SPL exceptions are ambiguous; do not use them

ErrorException is corrupted in PHP < 5.3
*/



/**
 * The exception that is thrown when the value of an argument is
 * outside the allowable range of values as defined by the invoked method.
 * @package    Nette
 */
class ArgumentOutOfRangeException extends InvalidArgumentException
{
}



/**
 * The exception that is thrown when a method call is invalid for the object's
 * current state, method has been invoked at an illegal or inappropriate time.
 * @package    Nette
 */
class InvalidStateException extends RuntimeException
{
}



/**
 * The exception that is thrown when a requested method or operation is not implemented.
 * @package    Nette
 */
class NotImplementedException extends LogicException
{
}



/**
 * The exception that is thrown when an invoked method is not supported. For scenarios where
 * it is sometimes possible to perform the requested operation, see InvalidStateException.
 * @package    Nette
 */
class NotSupportedException extends LogicException
{
}



/**
 * The exception that is thrown when a requested method or operation is deprecated.
 * @package    Nette
 */
class DeprecatedException extends NotSupportedException
{
}



/**
 * The exception that is thrown when accessing a class member (property or method) fails.
 * @package    Nette
 */
class MemberAccessException extends LogicException
{
}



/**
 * The exception that is thrown when an I/O error occurs.
 * @package    Nette
 */
class IOException extends RuntimeException
{
}



/**
 * The exception that is thrown when accessing a file that does not exist on disk.
 * @package    Nette
 */
class FileNotFoundException extends IOException
{
}



/**
 * The exception that is thrown when part of a file or directory cannot be found.
 * @package    Nette
 */
class DirectoryNotFoundException extends IOException
{
}



/**
 * The exception that indicates errors that can not be recovered from. Execution of
 * the script should be halted.
 * @package    Nette
 */
class FatalErrorException extends /*Error*/Exception
{

	public function __construct($message, $code, $severity, $file, $line, $context)
	{
		parent::__construct($message, $code);
		$this->file = $file;
		$this->line = $line;
		$this->context = $context;
	}

}
