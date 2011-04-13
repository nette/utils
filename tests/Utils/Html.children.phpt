<?php

/**
 * Test: Nette\Utils\Html basic usage.
 *
 * @author     David Grudl
 * @package    Nette\Web
 * @subpackage UnitTests
 */

use Nette\Utils\Html;



require __DIR__ . '/../bootstrap.php';



// add
$el = Html::el('ul');
$el->create('li')->setText('one');
$el->add( Html::el('li')->setText('two') )->class('hello');
Assert::same( '<ul class="hello"><li>one</li><li>two</li></ul>', (string) $el );


// with indentation
Assert::match( '
		<ul class="hello">
			<li>one</li>

			<li>two</li>
		</ul>
', $el->render(2), 'indentation' );



$el = Html::el(NULL);
$el->add( Html::el('p')->setText('one') );
$el->add( Html::el('p')->setText('two') );
Assert::same( '<p>one</p><p>two</p>', (string) $el );


// ==> Get child:
Assert::true( isset($el[1]), 'Child1' );
Assert::same( '<p>two</p>', (string) $el[1] );
Assert::false( isset($el[2]), 'Child2' );



// ==> Iterator:
$el = Html::el('select');
$el->create('optgroup')->label('Main')->create('option')->setText('sub one')->create('option')->setText('sub two');
$el->create('option')->setText('Item');
Assert::same( '<select><optgroup label="Main"><option>sub one<option>sub two</option></option></optgroup><option>Item</option></select>', (string) $el );
Assert::same( 2, count($el) );
Assert::same( "optgroup", $el[0]->getName() );
Assert::same( "option", $el[1]->getName() );


// ==> Deep iterator:
foreach ($el->getIterator(TRUE) as $name => $child) {
	$tmp[] = $child instanceof Html ? $child->getName() : "'$child'";
}
Assert::same( array(
	"optgroup",
	"option",
	"'sub one'",
	"option",
	"'sub two'",
	"option",
	"'Item'",
), $tmp );
