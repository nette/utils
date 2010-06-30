<?php

/**
 * Test: Nette\SmartCachingIterator constructor.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\SmartCachingIterator;



require __DIR__ . '/../initialize.php';



T::note("==> array");

$arr = array('Nette', 'Framework');
foreach (new SmartCachingIterator($arr) as $k => $v) T::dump("$k => $v");



T::note("==> stdClass");

$arr = (object) array('Nette', 'Framework');
foreach (new SmartCachingIterator($arr) as $k => $v) T::dump("$k => $v");



T::note("==> IteratorAggregate");

$arr = new ArrayObject(array('Nette', 'Framework'));
foreach (new SmartCachingIterator($arr) as $k => $v) T::dump("$k => $v");



T::note("==> Iterator");

foreach (new SmartCachingIterator($arr->getIterator()) as $k => $v) T::dump("$k => $v");



T::note("==> SimpleXMLElement");

$arr = new SimpleXMLElement('<feed><item>Nette</item><item>Framework</item></feed>');
foreach (new SmartCachingIterator($arr) as $k => $v) T::dump("$k => $v");



T::note("==> object");

try {
	$arr = dir('.');
	foreach (new SmartCachingIterator($arr) as $k => $v) T::dump("$k => $v");
} catch (Exception $e) {
	T::dump($e);
}



__halt_compiler() ?>

------EXPECT------
==> array

"0 => Nette"

"1 => Framework"

==> stdClass

"0 => Nette"

"1 => Framework"

==> IteratorAggregate

"0 => Nette"

"1 => Framework"

==> Iterator

"0 => Nette"

"1 => Framework"

==> SimpleXMLElement

"item => Nette"

"item => Framework"

==> object

Exception InvalidArgumentException: Invalid argument passed to foreach resp. %ns%SmartCachingIterator; array or Traversable expected, Directory given.
