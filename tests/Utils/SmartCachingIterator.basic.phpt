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



require __DIR__ . '/../NetteTest/initialize.php';



output("==> Two items in array");

$arr = array('Nette', 'Framework');

foreach ($iterator = new SmartCachingIterator($arr) as $k => $v)
{
	dump( $iterator->isFirst(), 'first' );
	dump( $iterator->isLast(), 'last' );
	dump( $iterator->getCounter(), 'counter' );

	foreach ($innerIterator = new SmartCachingIterator($arr) as $k => $v)
	{
		dump( $innerIterator->isFirst(), '  inner first' );
		dump( $innerIterator->isLast(), '  inner last' );
		dump( $innerIterator->counter, '  inner counter' );
	}
}

$iterator->rewind();
output("==> rewinding...");
dump( $iterator->isFirst(), 'first' );
dump( $iterator->isLast(), 'last' );
dump( $iterator->getCounter(), 'counter' );
dump( $iterator->isEmpty(), 'empty' );



output("==> One item in array");

$arr = array('Nette');

foreach ($iterator = new SmartCachingIterator($arr) as $k => $v)
{
	dump( $iterator->isFirst(), 'first' );
	dump( $iterator->isLast(), 'last' );
	dump( $iterator->getCounter(), 'counter' );
}

$iterator->rewind();
output("==> rewinding...");
dump( $iterator->isFirst(), 'first' );
dump( $iterator->isLast(), 'last' );
dump( $iterator->getCounter(), 'counter' );
dump( $iterator->isEmpty(), 'empty' );



output("==> Zero item in array");

$arr = array();

$iterator = new SmartCachingIterator($arr);
$iterator->next();
$iterator->next();
dump( $iterator->isFirst(), 'first' );
dump( $iterator->isLast(), 'last' );
dump( $iterator->getCounter(), 'counter' );
dump( $iterator->isEmpty(), 'empty' );



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
