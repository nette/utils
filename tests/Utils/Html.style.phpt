<?php

/**
 * Test: Nette\Utils\Html style & class attribute.
 *
 * @author     David Grudl
 * @package    Nette\Web
 * @subpackage UnitTests
 */

use Nette\Utils\Html;



require __DIR__ . '/../bootstrap.php';



$el = Html::el('div');
$el->style[] = 'text-align:right';
$el->style[] = NULL;
$el->style[] = 'background-color: blue';
$el->class[] = 'one';
$el->class[] = NULL;
$el->class[] = 'two';

Assert::same( '<div style="text-align:right;background-color: blue" class="one two"></div>', (string) $el );


$el->style = NULL;
$el->style['text-align'] = 'left';
$el->style['background-color'] = 'green';
Assert::same( '<div style="text-align:left;background-color:green" class="one two"></div>', (string) $el );


// append
$el = Html::el('div');
$el->style('color', 'white');
$el->style('background-color', 'blue');

$el->class = 'one';
$el->class('', TRUE);
$el->class('two', TRUE);

$el->id('my', TRUE);
Assert::same( '<div style="color:white;background-color:blue" class="one two" id="my"></div>', (string) $el );


// append II
$el = Html::el('div');
$el->style[] = 'text-align:right';
$el->style('', TRUE);
$el->style('background-color: blue', TRUE);
Assert::same( '<div style="text-align:right;background-color: blue"></div>', (string) $el );


// append III
$el = Html::el('div');
$el->class('top', TRUE);
$el->class('active', TRUE);
Assert::same( '<div class="top active"></div>', (string) $el );


$el->class('top', NULL);
$el->class('active', FALSE);
Assert::same( '<div></div>', (string) $el );
