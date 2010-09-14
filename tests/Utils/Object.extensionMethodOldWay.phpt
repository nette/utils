<?php

/**
 * Test: Nette\Object extension method old way.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Object.inc';



if (NETTE_PACKAGE === '5.3') {
	TestHelpers::skip('Requires Nette Framework package < PHP 5.3');
}



function TestClass_prototype_join(TestClass $that, $separator)
{
	return $that->foo . $separator . $that->bar;
}

$obj = new TestClass('Hello', 'World');
Assert::same( 'Hello*World', $obj->join('*') );
