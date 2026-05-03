<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\FileSystem isValidFilename()
 */

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// empty / `.` / `..` rejected
Assert::false(FileSystem::isValidFilename(''));
Assert::false(FileSystem::isValidFilename('.'));
Assert::false(FileSystem::isValidFilename('..'));

// plain filenames accepted
Assert::true(FileSystem::isValidFilename('file'));
Assert::true(FileSystem::isValidFilename('file.txt'));
Assert::true(FileSystem::isValidFilename('file.txt.bak'));
Assert::true(FileSystem::isValidFilename('My Document.docx'));
Assert::true(FileSystem::isValidFilename('faktura-březen.pdf')); // Unicode
Assert::true(FileSystem::isValidFilename('.gitignore'));         // hidden file is OK
Assert::true(FileSystem::isValidFilename('CONFIG'));             // not a reserved name (longer than CON)
Assert::true(FileSystem::isValidFilename('COM10.log'));          // only COM1–9 are reserved
Assert::true(FileSystem::isValidFilename('Console.log'));        // CON is a prefix, not the stem

// path separators rejected
Assert::false(FileSystem::isValidFilename('foo/bar'));
Assert::false(FileSystem::isValidFilename('foo\bar'));
Assert::false(FileSystem::isValidFilename('/etc/passwd'));
Assert::false(FileSystem::isValidFilename('C:\Windows'));

// null byte and control characters rejected
Assert::false(FileSystem::isValidFilename("foo\0bar"));
Assert::false(FileSystem::isValidFilename("foo\tbar"));
Assert::false(FileSystem::isValidFilename("foo\nbar"));

// Windows-reserved characters rejected
foreach (['<', '>', ':', '"', '|', '?', '*'] as $ch) {
	Assert::false(FileSystem::isValidFilename("foo{$ch}bar"), "char: $ch");
}

// trailing dot or space rejected (Windows silently strips them)
Assert::false(FileSystem::isValidFilename('foo.'));
Assert::false(FileSystem::isValidFilename('foo '));
Assert::false(FileSystem::isValidFilename('foo.txt.'));
Assert::false(FileSystem::isValidFilename('foo.txt '));

// Windows reserved device names rejected (case-insensitive, with or without an extension)
foreach (['CON', 'PRN', 'AUX', 'NUL'] as $name) {
	Assert::false(FileSystem::isValidFilename($name), $name);
	Assert::false(FileSystem::isValidFilename(strtolower($name)), strtolower($name));
	Assert::false(FileSystem::isValidFilename($name . '.txt'), $name . '.txt');
}
foreach (['COM1', 'COM5', 'COM9', 'LPT1', 'LPT5', 'LPT9'] as $name) {
	Assert::false(FileSystem::isValidFilename($name), $name);
	Assert::false(FileSystem::isValidFilename($name . '.log'), $name . '.log');
}
