<?php

/**
 * Test: Nette\Web\Html user data attribute.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

use Nette\Web\Html;



require __DIR__ . '/../initialize.php';



$el = Html::el('div');
$el->data['a'] = 'one';
$el->data['b'] = NULL;
$el->data['c'] = FALSE;
$el->data['d'] = '';
$el->data['e'] = 'two';

T::dump( (string) $el );

// direct
$el = Html::el('div');
$el->{'data-x'} = 'x';
$el->data['x'] = 'y';

T::dump( (string) $el );

// function
$el = Html::el('div');
$el->data('a', 'one');
$el->data('b', 'two');

T::dump( (string) $el );

$el = Html::el('div');
$el->data('top', NULL);
$el->data('active', FALSE);
$el->data('x', '');
T::dump( (string) $el );

$el = Html::el('div');
$el->data = 'simple';
T::dump( (string) $el );



__halt_compiler() ?>

------EXPECT------
"<div data-a="one" data-d="" data-e="two"></div>"

"<div data-x="x" data-x="y"></div>"

"<div data-a="one" data-b="two"></div>"

"<div data-x=""></div>"

"<div data="simple"></div>"
