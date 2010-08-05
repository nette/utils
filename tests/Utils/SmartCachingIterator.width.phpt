<?php

/**
 * Test: Nette\SmartCachingIterator width.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\SmartCachingIterator;



require __DIR__ . '/../initialize.php';



$arr = array('The', 'Nette', 'Framework');

foreach ($iterator = new SmartCachingIterator($arr) as $k => $v)
{
	T::note( "item $k" );
	T::dump( $iterator->isFirst(0), 'first(0)' );
	T::dump( $iterator->isLast(0), 'last(0)' );
	T::dump( $iterator->isFirst(1), 'first(1)' );
	T::dump( $iterator->isLast(1), 'last(1)' );
	T::dump( $iterator->isFirst(2), 'first(2)' );
	T::dump( $iterator->isLast(2), 'last(2)' );
}



$iterator = new SmartCachingIterator(array());

Assert::same( FALSE,  $iterator->isFirst(0) );
Assert::same( TRUE,  $iterator->isLast(0) );
Assert::same( FALSE,  $iterator->isFirst(1) );
Assert::same( TRUE,  $iterator->isLast(1) );
Assert::same( FALSE,  $iterator->isFirst(2) );
Assert::same( TRUE,  $iterator->isLast(2) );

__halt_compiler() ?>

------EXPECT------
item 0

first(0): TRUE

last(0): FALSE

first(1): TRUE

last(1): TRUE

first(2): TRUE

last(2): FALSE

item 1

first(0): FALSE

last(0): FALSE

first(1): TRUE

last(1): TRUE

first(2): FALSE

last(2): TRUE

item 2

first(0): FALSE

last(0): TRUE

first(1): TRUE

last(1): TRUE

first(2): TRUE

last(2): TRUE
