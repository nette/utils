<?php

/**
 * Test: Nette\ObjectMixin::checkType()
 *
 * @author     David Grudl
 * @package    Nette
 */

use Nette\ObjectMixin;


require __DIR__ . '/../bootstrap.php';


class StrClass
{
	function __toString()
	{
		return '1';
	}
}

function assertAccepts($type, $vals)
{
	foreach ($vals as $key => $val) {
		Assert::true( ObjectMixin::checkType($val, $type) );
		Assert::same( $vals[$key], $val);
	}
}

function assertRejects($type, $vals)
{
	foreach ($vals as $key => $val) {
		Assert::false( ObjectMixin::checkType($val, $type) );
		Assert::same( $vals[$key], $val);
	}
}

function assertConverts($type, $vals)
{
	foreach ($vals as $val) {
		Assert::true( ObjectMixin::checkType($val[0], $type) );
		Assert::same( $val[1], $val[0]);
	}
}


$resource = fopen(__FILE__, 'r');

assertAccepts('', array(NULL, TRUE, FALSE, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));
assertAccepts('mixed', array(NULL, TRUE, FALSE, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));

assertAccepts('scalar', array(TRUE, FALSE, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0'));
assertRejects('scalar', array(NULL, array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));

assertAccepts('boolean', array(TRUE, FALSE));
assertAccepts('bool', array(TRUE, FALSE));
assertRejects('bool', array(array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));
assertConverts('bool', array(
	array(NULL, FALSE),
	array(0, FALSE),
	array(0.0, FALSE),
	array(0.1, TRUE),
	array(1, TRUE),
	array(12, TRUE),
	array('', FALSE),
	array('0', FALSE),
	array('false', TRUE),
	array('-123', TRUE),
	array('123x', TRUE),
));

assertAccepts('string', array('', 'true', 'false', '-123', '123x', '+1.2', '1.0'));
assertRejects('string', array(array(), array(1, 2), array('a', 'b'), new stdClass, $resource));
assertConverts('string', array(
	array(NULL, ''),
	array(TRUE, '1'),
	array(FALSE, ''),
	array(0, '0'),
	array(0.0, '0'),
	array(0.1, '0.1'),
	array(1, '1'),
	array(12, '12'),
	array(new StrClass, '1'),
));

assertAccepts('integer', array(0, 1, 12));
assertAccepts('int', array(0, 1, 12));
assertRejects('int', array(0.1, '', 'true', 'false', '123x', '+1.2', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));
assertConverts('int', array(
	array(NULL, 0),
	array(TRUE, 1),
	array(FALSE, 0),
	array(0.0, 0),
	array('-123', -123),
	array('1.0', 1),
));

assertAccepts('float', array(0.0, 0.1));
assertRejects('float', array('', 'true', 'false', '123x', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));
assertConverts('float', array(
	array(NULL, 0.0),
	array(TRUE, 1.0),
	array(FALSE, 0.0),
	array(0, 0.0),
	array(1, 1.0),
	array(12, 12.0),
	array('-123', -123.0),
	array('+1.2', 1.2),
	array('1.0', 1.0),
));

assertAccepts('array', array(array(), array(1, 2), array('a', 'b')));
assertRejects('array', array(NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', new stdClass, new StrClass, $resource));

assertAccepts('object', array(new stdClass, new StrClass));
assertRejects('object', array(NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', array(), array(1, 2), array('a', 'b'), $resource));

assertAccepts('callable', array(array(new StrClass, '__toString')));
assertRejects('callable', array(NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));

assertAccepts('resource', array($resource));
assertRejects('resource', array(NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass));

assertAccepts('stdClass', array(new stdClass));
assertRejects('stdClass', array(NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', array(), array(1, 2), array('a', 'b'), new StrClass, $resource));

assertAccepts('null', array(NULL));
assertAccepts('NULL', array(NULL));
assertRejects('NULL', array(TRUE, FALSE, 0, 0.1, 12, '', '123x', array(), array(1, 2), array('a', 'b'), new stdClass, new StrClass, $resource));

assertAccepts('int[]', array(array(), array(1, 2)));
assertRejects('int[]', array(NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', array('a', 'b'), new stdClass, new StrClass, $resource));
assertConverts('int[]', array(
	array(array('1'), array(1)),
));

$val = array('1', new stdClass);
ObjectMixin::checkType($val, 'int[]');
Assert::equal( array('1', new stdClass), $val ); // do not modify

assertAccepts('array|string', array('', '123x', array(), array(1, 2), array('a', 'b')));
assertRejects('array|string', array(new stdClass, $resource));
assertConverts('array|string', array(
	array(NULL, ''),
	array(TRUE, '1'),
	array(FALSE, ''),
	array(0, '0'),
	array(0.0, '0'),
	array(0.1, '0.1'),
	array(1, '1'),
	array(12, '12'),
	array(new StrClass, '1'),
));


assertAccepts('string|bool|NULL', array(NULL, TRUE, FALSE, '', '123x'));
assertRejects('string|bool|NULL', array(array(), array(1, 2), array('a', 'b'), new stdClass, $resource));
assertConverts('string|bool|NULL', array(
	array(0, '0'),
	array(0.0, '0'),
	array(0.1, '0.1'),
	array(1, '1'),
	array(12, '12'),
	array(new StrClass, '1'),
));
