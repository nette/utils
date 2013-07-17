<?php

/**
 * Test: Nette\Utils\FileSystem basic usage.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\FileSystem;


require __DIR__ . '/../bootstrap.php';


test(function() { // createDir
	FileSystem::createDir(TEMP_DIR . '/1/b/');
	Assert::true( is_dir(TEMP_DIR . '/1/b') );

	FileSystem::createDir(TEMP_DIR . '/1/');

});

Assert::exception(function() {
	FileSystem::createDir('');
}, 'Nette\IOException', "Unable to create directory ''.");


test(function() { // write
	FileSystem::write(TEMP_DIR . '/2/file', 'Hello');
	Assert::true( is_file(TEMP_DIR . '/2/file') );
	Assert::same( 'Hello', file_get_contents(TEMP_DIR . '/2/file') );
});

Assert::exception(function() {
	FileSystem::write('', 'Hello');
}, 'Nette\IOException', "Unable to create directory ''.");


test(function() { // copy
	FileSystem::write(TEMP_DIR . '/3/file', 'Hello');

	FileSystem::copy(TEMP_DIR . '/3/file', TEMP_DIR . '/3/x/file');
	Assert::same( 'Hello', file_get_contents(TEMP_DIR . '/3/x/file') );

	FileSystem::copy('http://example.com', TEMP_DIR . '/3/x/y/file');
	Assert::true( is_file(TEMP_DIR . '/3/x/y/file') );

	FileSystem::write(TEMP_DIR . '/5/newfile', 'World');

	Assert::exception(function() {
		FileSystem::copy(TEMP_DIR . '/5/newfile', TEMP_DIR . '/3/x/file', FALSE);
	}, 'Nette\InvalidStateException', "File or directory '%a%' already exists.");
	Assert::same( 'Hello', file_get_contents(TEMP_DIR . '/3/x/file') );

	Assert::exception(function() {
		FileSystem::copy('http://example.com', TEMP_DIR . '/3/x/file', FALSE);
	}, 'Nette\InvalidStateException', "File or directory '%a%' already exists.");
	Assert::same( 'Hello', file_get_contents(TEMP_DIR . '/3/x/file') );

	FileSystem::copy(TEMP_DIR . '/5/newfile', TEMP_DIR . '/3/x/file');
	Assert::same( 'World', file_get_contents(TEMP_DIR . '/3/x/file') );

	Assert::exception(function() {
		FileSystem::copy(TEMP_DIR . '/5', TEMP_DIR . '/3', FALSE);
	}, 'Nette\InvalidStateException', "File or directory '%a%' already exists.");
	Assert::true( is_dir(TEMP_DIR . '/3/x/y') );
	Assert::false( file_exists(TEMP_DIR . '/3/newfile') );

	FileSystem::copy(TEMP_DIR . '/5', TEMP_DIR . '/3');
	Assert::false( file_exists(TEMP_DIR . '/3/x/y') );
	Assert::true( is_file(TEMP_DIR . '/3/newfile') );
});

Assert::exception(function() {
	FileSystem::copy(TEMP_DIR . '/6', TEMP_DIR . '/3');
}, 'Nette\IOException', "File or directory '%S%' not found.");


test(function() { // delete
	FileSystem::write(TEMP_DIR . '/7/file', 'Hello');
	FileSystem::delete(TEMP_DIR . '/7/file');
	Assert::true( is_dir(TEMP_DIR . '/7') );

	FileSystem::write(TEMP_DIR . '/7/file', 'Hello');
	FileSystem::delete(TEMP_DIR . '/7');
	Assert::false( file_exists(TEMP_DIR . '/7') );
});


test(function() { // rename
	FileSystem::write(TEMP_DIR . '/8/file', 'Hello');
	FileSystem::rename(TEMP_DIR . '/8', TEMP_DIR . '/9');
	FileSystem::rename(TEMP_DIR . '/9/file', TEMP_DIR . '/9/x/file');
	Assert::same( 'Hello', file_get_contents(TEMP_DIR . '/9/x/file') );

	FileSystem::write(TEMP_DIR . '/8/newfile', 'World');
	Assert::exception(function() {
		FileSystem::rename(TEMP_DIR . '/8/newfile', TEMP_DIR . '/9/x/file', FALSE);
	}, 'Nette\InvalidStateException', "File or directory '%a%' already exists.");
	Assert::same( 'Hello', file_get_contents(TEMP_DIR . '/9/x/file') );
	FileSystem::rename(TEMP_DIR . '/8/newfile', TEMP_DIR . '/9/x/file');
	Assert::same( 'World', file_get_contents(TEMP_DIR . '/9/x/file') );

	FileSystem::createDir(TEMP_DIR . '/10/');
	Assert::exception(function() {
		FileSystem::rename(TEMP_DIR . '/10', TEMP_DIR . '/9', FALSE);
	}, 'Nette\InvalidStateException', "File or directory '%a%' already exists.");
	Assert::same( 'World', file_get_contents(TEMP_DIR . '/9/x/file') );

	FileSystem::rename(TEMP_DIR . '/10', TEMP_DIR . '/9');
	Assert::false( file_exists(TEMP_DIR . '/9/x/file') );
	Assert::false( file_exists(TEMP_DIR . '/10') );
});

Assert::exception(function() {
	FileSystem::rename(TEMP_DIR . '/10', TEMP_DIR . '/9');
}, 'Nette\IOException', "File or directory '%S%' not found.");

Assert::exception(function() {
	FileSystem::rename(TEMP_DIR . '/9', TEMP_DIR . '/9');
}, 'Nette\IOException', "Unable to rename file or directory '%a%' to '%a%'.");


test(function() { // isAbsolute
	Assert::false( FileSystem::isAbsolute('') );
	Assert::true( FileSystem::isAbsolute('\\') );
	Assert::true( FileSystem::isAbsolute('//') );
	Assert::false( FileSystem::isAbsolute('file') );
	Assert::false( FileSystem::isAbsolute('dir:/file') );
	Assert::false( FileSystem::isAbsolute('dir:\file') );
	Assert::true( FileSystem::isAbsolute('d:/file') );
	Assert::true( FileSystem::isAbsolute('d:\file') );
	Assert::true( FileSystem::isAbsolute('D:\file') );
	Assert::true( FileSystem::isAbsolute('http://file') );
});
