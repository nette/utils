<?php

/**
 * Test: Nette\Utils\Strings::expand()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



Assert::same( 'item', Strings::expand('item', array()) );
Assert::same( '123', Strings::expand(123, array()) );
Assert::same( '%', Strings::expand('%%', array()) );
Assert::same( 'item', Strings::expand('%key%', array('key' => 'item')) );
Assert::same( 123, Strings::expand('%key%', array('key' => 123)) );
Assert::same( 'a123b123c', Strings::expand('a%key%b%key%c', array('key' => 123)) );
Assert::same( 123, Strings::expand('%key1.key2%', array('key1' => array('key2' => 123))) );
Assert::same( 123, Strings::expand('%key1%', array('key1' => '%key2%', 'key2' => 123), TRUE) );

Assert::throws(function() {
	Strings::expand('%missing%', array());
}, 'Nette\InvalidArgumentException', "Missing item 'missing'.");

Assert::throws(function() {
	Strings::expand('%key1%a', array('key1' => array('key2' => 123)));
}, 'Nette\InvalidArgumentException', "Unable to concatenate non-scalar parameter 'key1' into '%key1%a'.");

Assert::throws(function() {
	Strings::expand('%key1%', array('key1' => '%key2%', 'key2' => '%key1%'), TRUE);
}, 'Nette\InvalidArgumentException', "Circular reference detected for variables: key1, key2.");
