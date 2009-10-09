<?php

/**
 * Test: Nette\Object extension method old way.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



if (/*Nette\*/Framework::PACKAGE === 'PHP 5.3') {
	NetteTestHelpers::skip('Requires Nette Framework package < PHP 5.3);
}



function TestClass_prototype_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}

$obj = new TestClass('Hello', 'World');
dump( $obj->join('*') );



__halt_compiler();

------EXPECT------
string(11) "Hello*World"
