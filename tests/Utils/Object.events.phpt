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
T::dump( $var->counter );


$obj->onPublic[] = new Handler;

$obj->onPublic($var);
T::dump( $var->counter );


try {
	$obj->onPrivate(123);
	$this->fail('called private event');

} catch (MemberAccessException $e) {
	T::dump( $e );
}


try {
	$obj->onUndefined(123);
	$this->fail('called undefined event');

} catch (MemberAccessException $e) {
	T::dump( $e );
}



__halt_compiler() ?>

------EXPECT------
int(1)

int(3)

Exception MemberAccessException: Call to undefined method TestClass::onPrivate().

Exception MemberAccessException: Call to undefined method TestClass::onUndefined().
