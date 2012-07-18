<?php

/**
 * Test: Nette\Object extension method old way.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';

if (NETTE_PACKAGE === '5.3') {
	TestHelpers::skip('Requires Nette Framework package < PHP 5.3');
}



class TestClass extends Nette\Object
{
	public $foo = 'Hello', $bar = 'World';
}


function TestClass_prototype_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}

$obj = new TestClass;
Assert::same( 'Hello*World', $obj->join('*') );
