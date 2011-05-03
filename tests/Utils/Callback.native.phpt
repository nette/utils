<?php

/**
 * Test: Nette\Callback tests.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Callback;



require __DIR__ . '/../bootstrap.php';



class Test
{
	static function add($a, $b)
	{
		return $a + $b;
	}

	function __invoke()
	{
	}

}


Assert::same( 'undefined', callback('undefined')->getNative() );
Assert::same( 'undefined', (string) callback('undefined') );

Assert::same( 'trim', callback('trim')->getNative() );
Assert::same( 'trim', (string) callback('trim') );
Assert::same( 'Function trim()', (string) callback('trim')->toReflection() );

Assert::same( array('Test', 'add'), callback('Test', 'add')->getNative() );
Assert::same( 'Test::add', (string) callback('Test', 'add') );
Assert::same( 'Method Test::add()', (string) callback('Test', 'add')->toReflection() );

Assert::same( array('Test', 'add'), callback('Test::add')->getNative() );
Assert::same( 'Test::add', (string) callback('Test::add') );
Assert::same( 'Method Test::add()', (string) callback('Test::add')->toReflection() );

$test = new Test;
Assert::same( array($test, 'add'), callback($test, 'add')->getNative() );
Assert::same( 'Test::add', (string) callback($test, 'add') );
Assert::same( 'Method Test::add()', (string) callback($test, 'add')->toReflection() );

Assert::same( array($test, '__invoke'), callback($test)->getNative() );
Assert::same( 'Test::__invoke', (string) callback($test) );
Assert::same( 'Method Test::__invoke()', (string) callback($test)->toReflection() );

/**/
$closure = function(){};
Assert::same( $closure, callback($closure)->getNative() );
Assert::same( 'Closure::__invoke', (string) callback($closure) );
Assert::same( 'Function {closure}()', (string) callback($closure)->toReflection() );
/**/
