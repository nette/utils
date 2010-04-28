<?php

/**
 * Test: Nette\SmartCachingIterator constructor.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\SmartCachingIterator;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



output("==> array");

$arr = array('Nette', 'Framework');
foreach (new SmartCachingIterator($arr) as $k => $v) dump("$k => $v");



output("==> stdClass");

$arr = (object) array('Nette', 'Framework');
foreach (new SmartCachingIterator($arr) as $k => $v) dump("$k => $v");



output("==> IteratorAggregate");

$arr = new ArrayObject(array('Nette', 'Framework'));
foreach (new SmartCachingIterator($arr) as $k => $v) dump("$k => $v");



output("==> Iterator");

foreach (new SmartCachingIterator($arr->getIterator()) as $k => $v) dump("$k => $v");



output("==> SimpleXMLElement");

$arr = new SimpleXMLElement('<feed><item>Nette</item><item>Framework</item></feed>');
foreach (new SmartCachingIterator($arr) as $k => $v) dump("$k => $v");



output("==> object");

try {
	$arr = dir('.');
	foreach (new SmartCachingIterator($arr) as $k => $v) dump("$k => $v");
} catch (Exception $e) {
	dump($e);
}



__halt_compiler();

------EXPECT------
==> array

string(10) "0 => Nette"

string(14) "1 => Framework"

==> stdClass

string(10) "0 => Nette"

string(14) "1 => Framework"

==> IteratorAggregate

string(10) "0 => Nette"

string(14) "1 => Framework"

==> Iterator

string(10) "0 => Nette"

string(14) "1 => Framework"

==> SimpleXMLElement

string(13) "item => Nette"

string(17) "item => Framework"

==> object

Exception InvalidArgumentException: Invalid argument passed to foreach resp. SmartCachingIterator; array or Traversable expected, Directory given.
