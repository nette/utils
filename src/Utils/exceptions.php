<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette
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
 * @package    exceptions
 */
class ArgumentOutOfRangeException extends InvalidArgumentException
{
}



/**
 * The exception that is thrown when a method call is invalid for the object's
 * current state, method has been invoked at an illegal or inappropriate time.
 * @package    exceptions
 */
class InvalidStateException extends RuntimeException
{
	/*5.2*
	public function __construct($message = '', $code = 0, Exception $previous = NULL)
	{
		if (PHP_VERSION_ID < 50300) {
			$this->previous = $previous;
			parent::__construct($message, $code);
		} else {
			parent::__construct($message, $code, $previous);
		}
	}
	*/
}



/**
 * The exception that is thrown when a requested method or operation is not implemented.
 * @package    exceptions
 */
class NotImplementedException extends LogicException
{
}



/**
 * The exception that is thrown when an invoked method is not supported. For scenarios where
 * it is sometimes possible to perform the requested operation, see InvalidStateException.
 * @package    exceptions
 */
class NotSupportedException extends LogicException
{
}



/**
 * The exception that is thrown when a requested method or operation is deprecated.
 * @package    exceptions
 */
class DeprecatedException extends NotSupportedException
{
}



/**
 * The exception that is thrown when accessing a class member (property or method) fails.
 * @package    exceptions
 */
class MemberAccessException extends LogicException
{
}



/**
 * The exception that is thrown when an I/O error occurs.
 * @package    exceptions
 */
class IOException extends RuntimeException
{
}



/**
 * The exception that is thrown when accessing a file that does not exist on disk.
 * @package    exceptions
 */
class FileNotFoundException extends IOException
{
}



/**
 * The exception that is thrown when part of a file or directory cannot be found.
 * @package    exceptions
 */
class DirectoryNotFoundException extends IOException
{
}



/**
 * The exception that indicates errors that can not be recovered from. Execution of
 * the script should be halted.
 * @package    exceptions
 */
/**/
class FatalErrorException extends ErrorException
{

	public function __construct($message, $code, $severity, $file, $line, $context)
	{
		parent::__construct($message, $code, $severity, $file, $line);
		$this->context = $context;
	}

}
/**/

/*5.2*
class FatalErrorException extends Exception
{
	private $severity;

	public function __construct($message, $code, $severity, $file, $line, $context)
	{
		parent::__construct($message, $code);
		$this->severity = $severity;
		$this->file = $file;
		$this->line = $line;
		$this->context = $context;
	}

	public function getSeverity()
	{
		return $this->severity;
	}

}
*/
