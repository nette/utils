<?php

/**
 * Test: Nette\Object extension method via interface.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



function IFirst_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



function ISecond_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



Object::extensionMethod('IFirst::join', 'IFirst_join');
Object::extensionMethod('ISecond::join', 'ISecond_join');

$obj = new TestClass('Hello', 'World');
Assert::same( 'ISecond_join says Hello*World', $obj->join('*') );
