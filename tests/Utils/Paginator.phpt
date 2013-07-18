<?php

/**
 * Test: Nette\Utils\Paginator Base:0 Page:3 test.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Paginator;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$paginator = new Paginator;
	$paginator->itemCount = 7;
	$paginator->itemsPerPage = 6;
	$paginator->base = 0;
	$paginator->page = 3;

	Assert::same( 1, $paginator->page );
	Assert::same( 2, $paginator->pageCount );
	Assert::same( 0, $paginator->firstPage );
	Assert::same( 1, $paginator->lastPage );
	Assert::same( 6, $paginator->offset );
	Assert::same( 0, $paginator->countdownOffset );
	Assert::same( 1, $paginator->length );
});


test(function() {
	$paginator = new Paginator;
	$paginator->itemCount = 7;
	$paginator->itemsPerPage = 6;
	$paginator->base = 0;
	$paginator->page = -1;

	Assert::same( 0, $paginator->page );
	Assert::same( 0, $paginator->offset );
	Assert::same( 1, $paginator->countdownOffset );
	Assert::same( 6, $paginator->length );
});


test(function() {
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
});


test(function() {
	$paginator = new Paginator;
	$paginator->itemCount = -1;
	$paginator->itemsPerPage = 7;
	$paginator->base = 0;
	$paginator->page = -1;

	Assert::same( 0, $paginator->page );
	Assert::same( 0, $paginator->pageCount );
	Assert::same( 0, $paginator->firstPage );
	Assert::same( 0, $paginator->lastPage );
	Assert::same( 0, $paginator->offset );
	Assert::same( 0, $paginator->countdownOffset );
	Assert::same( 0, $paginator->length );
});


test(function() {
	$paginator = new Paginator;
	$paginator->itemCount = 7;
	$paginator->itemsPerPage = 6;
	$paginator->base = 1;
	$paginator->page = 3;

	Assert::same( 2, $paginator->page );
	Assert::same( 2, $paginator->pageCount );
	Assert::same( 1, $paginator->firstPage );
	Assert::same( 2, $paginator->lastPage );
	Assert::same( 6, $paginator->offset );
	Assert::same( 0, $paginator->countdownOffset );
	Assert::same( 1, $paginator->length );
});


test(function() {
	$paginator = new Paginator;

	// ItemCount: 0
	$paginator->setItemCount(0);
	Assert::true( $paginator->isFirst() );
	Assert::true( $paginator->isLast() );


	// ItemCount: 1
	$paginator->setItemCount(1);
	Assert::true( $paginator->isFirst() );
	Assert::true( $paginator->isLast() );


	// ItemCount: 2
	$paginator->setItemCount(2);
	Assert::true( $paginator->isFirst() );
	Assert::false( $paginator->isLast() );

	// Page 2
	$paginator->setPage(2);
	Assert::false( $paginator->isFirst() );
	Assert::true( $paginator->isLast() );
});


test(function() {
	$paginator = new Paginator;
	$paginator->itemsPerPage = 6;
	$paginator->base = 0;
	$paginator->page = 3;

	Assert::same( 3, $paginator->page );
	Assert::null( $paginator->pageCount );
	Assert::same( 0, $paginator->firstPage );
	Assert::null( $paginator->lastPage );
	Assert::same( 18, $paginator->offset );
	Assert::null( $paginator->countdownOffset );
	Assert::same( 6, $paginator->length );
});
