<?php

/**
 * Test: Nette\Object event handlers.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';




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
dump( $var->counter );


$obj->onPublic[] = new Handler;

$obj->onPublic($var);
dump( $var->counter );


try {
	$obj->onPrivate(123);
	$this->fail('called private event');

} catch (MemberAccessException $e) {
	dump( $e );
}


try {
	$obj->onUndefined(123);
	$this->fail('called undefined event');

} catch (MemberAccessException $e) {
	dump( $e );
}



__halt_compiler();

------EXPECT------
int(1)

int(3)

Exception MemberAccessException: Call to undefined method TestClass::onPrivate().

Exception MemberAccessException: Call to undefined method TestClass::onUndefined().
