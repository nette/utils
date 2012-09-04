<?php

/**
 * Test: Nette\Utils\Paginator Base:0 Page:-1 test.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Paginator;



require __DIR__ . '/../bootstrap.php';



$paginator = new Paginator;
$paginator->itemCount = 7;
$paginator->itemsPerPage = 6;
$paginator->base = 0;
$paginator->page = -1;

Assert::same( 0, $paginator->page );
Assert::same( 0, $paginator->offset );
Assert::same( 1, $paginator->countdownOffset );
Assert::same( 6, $paginator->length );
