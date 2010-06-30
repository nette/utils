<?php

/**
 * Test: Nette\Object extension method via interface.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object,
	Nette\Reflection\ClassReflection;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Object.inc';



function IFirst_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



function ISecond_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



ClassReflection::from('IFirst')->setExtensionMethod('join', 'IFirst_join');
ClassReflection::from('ISecond')->setExtensionMethod('join', 'ISecond_join');

$obj = new TestClass('Hello', 'World');
T::dump( $obj->join('*') );



__halt_compiler() ?>

------EXPECT------
"ISecond_join says Hello*World"
