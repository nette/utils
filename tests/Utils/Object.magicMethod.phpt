<?php

/**
 * Test: Nette\Object magic @methods.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


/**
 * @method void setName($var)
 * @method getName()
 * @method self addItem()
 * @method self setItems()
 * @method getItems
 * @method setEnabled ( $enabled )
 * @method isEnabled () comment
 */
class TestClass extends Nette\Object
{
	public $name;

	protected $enabled;

	private $items = array();
}


$obj = new TestClass;

// public
Assert::same( $obj, $obj->setName('hello') );
Assert::same( 'hello', $obj->name );
Assert::same( 'hello', $obj->getName() );

// protected
Assert::same( $obj, $obj->setEnabled(11) );
Assert::same( 11, $obj->isEnabled() );

// magic accessors for magic methods
$obj->enabled = 22;
Assert::same( 22, $obj->enabled );

// adder
Assert::same( $obj, $obj->addItem('world') );
Assert::same( array('world'), $obj->items );
Assert::same( array('world'), $obj->getItems() );

Assert::same( $obj, $obj->setItems(array()) );
Assert::same( array(), $obj->items );
Assert::same( array(), $obj->getItems() );
