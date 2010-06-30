<?php

/**
 * Test: Nette\Web\Html style & class attribute.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

use Nette\Web\Html;



require __DIR__ . '/../initialize.php';



$el = Html::el('div');
$el->style[] = 'text-align:right';
$el->style[] = NULL;
$el->style[] = 'background-color: blue';
$el->class[] = 'one';
$el->class[] = NULL;
$el->class[] = 'two';

T::dump( (string) $el );

$el->style = NULL;
$el->style['text-align'] = 'left';
$el->style['background-color'] = 'green';
T::dump( (string) $el );

// append
$el = Html::el('div');
$el->style('color', 'white');
$el->style('background-color', 'blue');

$el->class = 'one';
$el->class('', TRUE);
$el->class('two', TRUE);

$el->id('my', TRUE);
T::dump( (string) $el );

// append II
$el = Html::el('div');
$el->style[] = 'text-align:right';
$el->style('', TRUE);
$el->style('background-color: blue', TRUE);
T::dump( (string) $el );

// append III
$el = Html::el('div');
$el->class('top', TRUE);
$el->class('active', TRUE);
T::dump( (string) $el );

$el->class('top', NULL);
$el->class('active', FALSE);
T::dump( (string) $el );



__halt_compiler() ?>

------EXPECT------
"<div style="text-align:right;background-color: blue" class="one two"></div>"

"<div style="text-align:left;background-color:green" class="one two"></div>"

"<div style="color:white;background-color:blue" class="one two" id="my"></div>"

"<div style="text-align:right;background-color: blue"></div>"

"<div class="top active"></div>"

"<div></div>"
