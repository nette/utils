<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette;


/**
 * The exception that is thrown when the value of an argument is
 * outside the allowable range of values as defined by the invoked method.
 */
class ArgumentOutOfRangeException extends \InvalidArgumentException
{
}


/**
 * The exception that is thrown when a method call is invalid for the object's
 * current state, method has been invoked at an illegal or inappropriate time.
 */
class InvalidStateException extends \RuntimeException
{
}


/**
 * The exception that is thrown when a requested method or operation is not implemented.
 */
class NotImplementedException extends \LogicException
{
}


/**
 * The exception that is thrown when an invoked method is not supported. For scenarios where
 * it is sometimes possible to perform the requested operation, see InvalidStateException.
 */
class NotSupportedException extends \LogicException
{
}


/**
 * The exception that is thrown when a requested method or operation is deprecated.
 */
class DeprecatedException extends NotSupportedException
{
}


/**
 * The exception that is thrown when accessing a class member (property or method) fails.
 */
class MemberAccessException extends \Error
{
}


/**
 * The exception that is thrown when an I/O error occurs.
 */
class IOException extends \RuntimeException
{
}


/**
 * The exception that is thrown when accessing a file that does not exist on disk.
 */
class FileNotFoundException extends IOException
{
}


/**
 * The exception that is thrown when part of a file or directory cannot be found.
 */
class DirectoryNotFoundException extends IOException
{
}


/**
 * The exception that is thrown when an argument does not match with the expected value.
 */
class InvalidArgumentException extends \InvalidArgumentException
{
}


/**
 * The exception that is thrown when an illegal index was requested.
 */
class OutOfRangeException extends \OutOfRangeException
{
}


/**
 * The exception that is thrown when a value (typically returned by function) does not match with the expected value.
 */
class UnexpectedValueException extends \UnexpectedValueException
{
}

namespace Nette\Utils;


/**
 * The exception that is thrown when an image error occurs.
 */
class ImageException extends \Exception
{
}


/**
 * The exception that indicates invalid image file.
 */
class UnknownImageFileException extends ImageException
{
}


/**
 * The exception that indicates error of JSON encoding/decoding.
 */
class JsonException extends \Exception
{
}


/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception
{
	public const MESSAGES = [
		PREG_INTERNAL_ERROR => 'Internal error',
		PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
		PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
		PREG_BAD_UTF8_ERROR => 'Malformed UTF-8 data',
		PREG_BAD_UTF8_OFFSET_ERROR => 'Offset didn\'t correspond to the begin of a valid UTF-8 code point',
		6 => 'Failed due to limited JIT stack space', // PREG_JIT_STACKLIMIT_ERROR
	];
}


/**
 * The exception that indicates assertion error.
 */
class AssertionException extends \Exception
{
}
