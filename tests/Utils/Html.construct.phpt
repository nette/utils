<?php

/**
 * Test: Nette\Web\Html::__construct()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

use Nette\Web\Html;



require __DIR__ . '/../initialize.php';



Assert::same( '<a lang="cs" href="#" title="" selected="selected">click</a>', (string) Html::el('a lang=cs href="#" title="" selected')->setText('click') );
Assert::same( '<a lang="hello" world="world" href="hello world" title="hello \'world">click</a>', (string) Html::el('a lang=hello world href="hello world" title="hello \'world"')->setText('click') );
Assert::same( '<a lang="hello&quot; world" href="hello " world="world" title="0">click</a>', (string) Html::el('a lang=\'hello" world\' href="hello "world" title=0')->setText('click') );
