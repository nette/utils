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

first: TRUE

last: FALSE

counter: 1

  inner first: TRUE

  inner last: FALSE

  inner counter: 1

  inner first: FALSE

  inner last: TRUE

  inner counter: 2

first: FALSE

last: TRUE

counter: 2

  inner first: TRUE

  inner last: FALSE

  inner counter: 1

  inner first: FALSE

  inner last: TRUE

  inner counter: 2

==> rewinding...

first: TRUE

last: FALSE

counter: 1

empty: FALSE

==> One item in array

first: TRUE

last: TRUE

counter: 1

==> rewinding...

first: TRUE

last: TRUE

counter: 1

empty: FALSE

==> Zero item in array

first: FALSE

last: TRUE

counter: 0

empty: TRUE
