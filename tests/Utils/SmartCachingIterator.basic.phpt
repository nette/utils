<?php

/**
 * Test: Nette\SmartCachingIterator basic usage.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\SmartCachingIterator;



require __DIR__ . '/../initialize.php';



T::note("==> Two items in array");

$arr = array('Nette', 'Framework');

foreach ($iterator = new SmartCachingIterator($arr) as $k => $v)
{
	T::dump( $iterator->isFirst(), 'first' );
	T::dump( $iterator->isLast(), 'last' );
	T::dump( $iterator->getCounter(), 'counter' );

	foreach ($innerIterator = new SmartCachingIterator($arr) as $k => $v)
	{
		T::dump( $innerIterator->isFirst(), '  inner first' );
		T::dump( $innerIterator->isLast(), '  inner last' );
		T::dump( $innerIterator->counter, '  inner counter' );
	}
}

$iterator->rewind();
T::note("==> rewinding...");
T::dump( $iterator->isFirst(), 'first' );
T::dump( $iterator->isLast(), 'last' );
T::dump( $iterator->getCounter(), 'counter' );
T::dump( $iterator->isEmpty(), 'empty' );



T::note("==> One item in array");

$arr = array('Nette');

foreach ($iterator = new SmartCachingIterator($arr) as $k => $v)
{
	T::dump( $iterator->isFirst(), 'first' );
	T::dump( $iterator->isLast(), 'last' );
	T::dump( $iterator->getCounter(), 'counter' );
}

$iterator->rewind();
T::note("==> rewinding...");
T::dump( $iterator->isFirst(), 'first' );
T::dump( $iterator->isLast(), 'last' );
T::dump( $iterator->getCounter(), 'counter' );
T::dump( $iterator->isEmpty(), 'empty' );



T::note("==> Zero item in array");

$arr = array();

$iterator = new SmartCachingIterator($arr);
$iterator->next();
$iterator->next();
T::dump( $iterator->isFirst(), 'first' );
T::dump( $iterator->isLast(), 'last' );
T::dump( $iterator->getCounter(), 'counter' );
T::dump( $iterator->isEmpty(), 'empty' );



__halt_compiler() ?>

------EXPECT------
==> Two items in array

first: bool(TRUE)

last: bool(FALSE)

counter: int(1)

  inner first: bool(TRUE)

  inner last: bool(FALSE)

  inner counter: int(1)

  inner first: bool(FALSE)

  inner last: bool(TRUE)

  inner counter: int(2)

first: bool(FALSE)

last: bool(TRUE)

counter: int(2)

  inner first: bool(TRUE)

  inner last: bool(FALSE)

  inner counter: int(1)

  inner first: bool(FALSE)

  inner last: bool(TRUE)

  inner counter: int(2)

==> rewinding...

first: bool(TRUE)

last: bool(FALSE)

counter: int(1)

empty: bool(FALSE)

==> One item in array

first: bool(TRUE)

last: bool(TRUE)

counter: int(1)

==> rewinding...

first: bool(TRUE)

last: bool(TRUE)

counter: int(1)

empty: bool(FALSE)

==> Zero item in array

first: bool(FALSE)

last: bool(TRUE)

counter: int(0)

empty: bool(TRUE)
