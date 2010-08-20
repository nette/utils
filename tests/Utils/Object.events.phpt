<?php

/**
 * Test: Nette\Object event handlers.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Object.inc';



function handler($obj)
{
	$obj->counter++;
}



class Handler
{
	function __invoke($obj)
	{
		$obj->counter++;
	}
}



$obj = new TestClass;
$var = (object) NULL;

$obj->onPublic[] = 'handler';

$obj->onPublic($var);
Assert::same( 1, $var->counter );



$obj->onPublic[] = new Handler;

$obj->onPublic($var);
Assert::same( 3, $var->counter );



try {
	$obj->onPrivate(123);
	$this->fail('called private event');

	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', 'Call to undefined method TestClass::onPrivate().', $e );
}


try {
	$obj->onUndefined(123);
	$this->fail('called undefined event');

	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', 'Call to undefined method TestClass::onUndefined().', $e );
}
