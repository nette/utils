<?php

/**
 * Test: Nette\Utils\Html::__construct()
 */

declare(strict_types=1);

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('<a lang="cs" href="#" title="" selected>click</a>', (string) Html::el('a lang=cs href="#" title="" selected')->setText('click'));
Assert::same('<a lang="hello" world href="hello world" title="hello \'world">click</a>', (string) Html::el('a lang=hello world href="hello world" title="hello \'world"')->setText('click'));
Assert::same('<a lang=\'hello" world\' href="hello " world title="0">click</a>', (string) Html::el('a lang=\'hello" world\' href="hello "world" title=0')->setText('click'));


Assert::type(Html::class, Html::fromHtml('xxx'));
Assert::same('<a href="#">hello&quot;</a>', (string) Html::fromHtml('<a href="#">hello&quot;</a>'));

Assert::type(Html::class, Html::fromText('xxx'));
Assert::same('&lt;a href="#"&gt;hello&amp;quot;&lt;/a&gt;', (string) Html::fromText('<a href="#">hello&quot;</a>'));
