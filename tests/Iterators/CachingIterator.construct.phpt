<?php

/**
 * Test: Nette\SmartCachingIterator constructor.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\SmartCachingIterator;



require __DIR__ . '/../bootstrap.php';



// ==> array

$arr = array('Nette', 'Framework');
$tmp = array();
foreach (new SmartCachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
Assert::same( array(
	'0 => Nette',
	'1 => Framework',
), $tmp );



// ==> stdClass

$arr = (object) array('Nette', 'Framework');
$tmp = array();
foreach (new SmartCachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
Assert::same( array(
	'0 => Nette',
	'1 => Framework',
), $tmp );



// ==> IteratorAggregate

$arr = new ArrayObject(array('Nette', 'Framework'));
$tmp = array();
foreach (new SmartCachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
Assert::same( array(
	'0 => Nette',
	'1 => Framework',
), $tmp );



// ==> Iterator

$tmp = array();
foreach (new SmartCachingIterator($arr->getIterator()) as $k => $v) $tmp[] = "$k => $v";
Assert::same( array(
	'0 => Nette',
	'1 => Framework',
), $tmp );



// ==> SimpleXMLElement

$arr = new SimpleXMLElement('<feed><item>Nette</item><item>Framework</item></feed>');
$tmp = array();
foreach (new SmartCachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
Assert::same( array(
	'item => Nette',
	'item => Framework',
), $tmp );



// ==> object

try {
	$arr = dir('.');
	foreach (new SmartCachingIterator($arr) as $k => $v);
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidArgumentException', NULL, $e );
}
