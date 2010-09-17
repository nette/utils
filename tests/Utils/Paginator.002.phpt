<?php

/**
 * Test: Nette\Paginator Base:0 Page:-1 PerPage:7 test.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Paginator;



require __DIR__ . '/../bootstrap.php';



$paginator = new Paginator;
$paginator->itemCount = 7;
$paginator->itemsPerPage = 7;
$paginator->base = 0;
$paginator->page = -1;

Assert::same( 0, $paginator->page );
Assert::same( 1, $paginator->pageCount );
Assert::same( 0, $paginator->firstPage );
Assert::same( 0, $paginator->lastPage );
Assert::same( 0, $paginator->offset );
Assert::same( 0, $paginator->countdownOffset );
Assert::same( 7, $paginator->length );
