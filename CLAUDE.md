# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Nette Utils is a PHP utility library providing lightweight tools for string/array manipulation, image handling, JSON encoding/decoding, validation, and more. It's part of the Nette Framework ecosystem but works standalone.

**Requirements:** PHP 8.2 - 8.5

## Essential Commands

### Testing
```bash
# Run all tests
composer run tester
# or
vendor/bin/tester tests -s -C

# Run specific test file
vendor/bin/tester tests/Utils/Arrays.get\(\).phpt -s -C

# Run tests in specific directory
vendor/bin/tester tests/Utils/ -s -C
```

### Static Analysis
```bash
# Run PHPStan
composer run phpstan
# or
vendor/bin/phpstan analyse
```

## Architecture

### Directory Structure

```
src/
├── exceptions.php              # All shared exceptions in single file
├── StaticClass.php             # Trait to prevent instantiation
├── SmartObject.php             # Trait for strict property/method access
├── HtmlStringable.php          # Interface for HTML-safe string conversion
├── Translator.php              # Interface for translations
├── compatibility.php           # Forward compatibility shims
├── Utils/                      # Main utility classes
│   ├── Arrays.php              # Array manipulation
│   ├── Strings.php             # String utilities
│   ├── Html.php                # HTML element generation
│   ├── Image.php               # Image manipulation
│   ├── Json.php                # JSON encoding/decoding
│   ├── Validators.php          # Input validation
│   ├── FileSystem.php          # File operations
│   ├── Finder.php              # File/directory search
│   ├── DateTime.php            # DateTime extensions
│   ├── Type.php                # Type introspection
│   ├── Reflection.php          # Reflection utilities
│   └── ...
└── Iterators/                  # Custom iterators
    ├── CachingIterator.php
    └── Mapper.php

tests/
├── bootstrap.php               # Test environment setup
├── Utils/                      # Tests mirror src/Utils structure
│   ├── Arrays.get().phpt       # Individual test files
│   ├── Arrays.filter().phpt
│   └── ...
└── Iterators/
```

### Design Patterns

**Static Utility Classes**: Most utility classes (Arrays, Strings, etc.) use the `StaticClass` trait to prevent instantiation. They provide only static methods.

**SmartObject Trait**: Used in instantiable classes (Html, Image, etc.) to provide:
- Strict property access (throws exceptions for undefined properties)
- "Did you mean" hints for typos
- Support for `@property` annotations
- Event handler support via `$onEvent` properties

**Exception Hierarchy**: All exceptions in single `src/exceptions.php` file:
- `InvalidArgumentException` - Invalid argument type/format
- `ArgumentOutOfRangeException` - Value outside allowed range
- `IOException` - File/stream operations failed
  - `FileNotFoundException` - File doesn't exist
  - `DirectoryNotFoundException` - Directory doesn't exist
- `InvalidStateException` - Object state doesn't allow operation
- `NotSupportedException` - Feature not supported
- `MemberAccessException` - Invalid property/method access
- `UnexpectedValueException` - Return value has wrong type

## Testing Conventions

Tests use **Nette Tester** with `.phpt` extension.

### Test File Structure

```php
<?php

declare(strict_types=1);

use Tester\Assert;
use Nette\Utils\Arrays;

require __DIR__ . '/../bootstrap.php';

test('descriptive test name', function () {
	$result = Arrays::get(['a' => 1], 'a');
	Assert::same(1, $result);
});

test('another test case', function () {
	// Test code
});
```

**Key conventions:**
- Use `test()` function for each test case
- First parameter is clear description (no additional comments needed)
- Use `testException()` when entire test should throw exception
- Group related tests in same file

### Common Assertions

```php
Assert::same($expected, $actual);           # Strict comparison (===)
Assert::equal($expected, $actual);          # Deep comparison
Assert::true($value);
Assert::false($value);
Assert::null($value);
Assert::type($type, $value);
Assert::exception(
	fn() => SomeClass::method(),
	ExceptionClass::class,
	'Expected message'  # %a% matches any text
);
```

### Test Helpers

- `getTempDir()` - Returns unique temp directory for test (defined in bootstrap.php)

## Coding Standards

### PHP Requirements

- Every file must have `declare(strict_types=1)`
- All properties, parameters, and return values must have types
- Use `readonly` for immutable properties
- Prefer modern PHP syntax (e.g., `??` null coalescing)

### Naming & Style

- Static utility classes use PascalCase (Arrays, Strings)
- Methods use camelCase
- Constants use SCREAMING_SNAKE_CASE
- Two-letter abbreviations: UPPERCASE (HTML, IO)
- Longer abbreviations: PascalCase/camelCase (Json, DateTime)

### Documentation

Follow the philosophy: **Don't duplicate signature information**

```php
// GOOD - adds value beyond signature
/**
 * Returns list of supported languages.
 * @return string[]  Array of language codes
 */
public function getSupportedLanguages(): array

// BAD - just repeats signature
/**
 * Gets the width.
 * @return int The width
 */
public function getWidth(): int

// GOOD - skip docs when signature is clear
public function getWidth(): int
```

**For exceptions:** Describe the problem, not context
- "The file does not exist." ✓
- "Exception thrown when file does not exist." ✗

### Type Annotations

Use generic type annotations for better IDE support:

```php
/**
 * @template T
 * @param  array<T>  $array
 * @return ?T
 */
public static function first(array $array): mixed

/**
 * @param  iterable<string>  $values
 * @return array<string>
 */
public static function normalize(iterable $values): array
```

## CI/CD

GitHub Actions runs on every push/PR:
1. **Tests** - PHP 8.2-8.5 on Ubuntu and Windows
2. **Coding Style** - Nette Code Checker and Coding Standard
3. **Static Analysis** - PHPStan (informative only, doesn't fail)
4. **Code Coverage** - Uploaded to Coveralls

## PHP Extensions

The library gracefully degrades without extensions, but some require:
- `ext-iconv` - For `Strings::webalize()`, `toAscii()`
- `ext-intl` - For `Strings::normalize()`, `compare()`
- `ext-mbstring` - For `Strings::lower()`, etc.
- `ext-gd` - For `Image` class
- `ext-json` - For `Json` class
- `ext-tokenizer` - For `Reflection::getUseStatements()`

Tests run with all extensions enabled to ensure full coverage.

## Key Utility Classes

### Arrays vs Iterables

**[Nette\Utils\Arrays](src/Utils/Arrays.php)** - Static class for working with arrays
**[Nette\Utils\Iterables](src/Utils/Iterables.php)** - Static class for working with iterators (equivalent API)

These classes provide parallel APIs - choose based on your data structure:
- Use `Arrays` when working with native PHP arrays
- Use `Iterables` when working with iterators, Generators, or any iterable

Both support similar operations: `contains()`, `every()`, `filter()`, `first()`, `firstKey()`, `map()`, etc.

**Important:** `Iterables` methods often return Generators for memory efficiency - filtering/mapping happens incrementally during iteration.

### Type - Working with PHP Types

**[Nette\Utils\Type](src/Utils/Type.php)** - Unified API for working with PHP's type system (union, intersection, DNF types)

Replaces the deprecated `Reflection::getParameterType()`, `getPropertyType()`, `getReturnType()` methods.

```php
use Nette\Utils\Type;

// Create from reflection
$type = Type::fromReflection($reflectionProperty);

// Create from string
$type = Type::fromString('int|string|null');

// Create from value
$type = Type::fromValue($variable);

// Check type compatibility
$type->allows($otherType);  // Can $type accept $otherType?

// Decompose complex types
$type->getNames();  // ['int', 'string', 'null']
$type->getTypes();  // [Type, Type, Type]
```

**Use case:** When you need to work with reflection types programmatically, especially complex union/intersection types.

### Validators - Type Validation with Expected Types

**[Nette\Utils\Validators](src/Utils/Validators.php)** - Validates values against "expected types" syntax

**Expected Types Syntax** - String notation for validation rules:
- Basic types: `int`, `string`, `bool`, `float`, `array`, `object`, `null`
- Union types: `int|string|bool`
- Nullable: `?int` (equivalent to `int|null`)
- Arrays with element types: `int[]`, `string[]`
- Ranges/constraints: `int:1..10`, `string:5`, `array:..100`, `list:10..20`
- Patterns: `pattern:[0-9]+`
- Pseudo-types: `list`, `unicode`, `numeric`, `numericint`, `number`, `none`

```php
use Nette\Utils\Validators;

// Basic validation
Validators::is($value, 'int|string');
Validators::isEmail($email);
Validators::isUrl($url);
Validators::isUnicode($string);

// Assert (throws exception on failure)
Validators::assert($value, 'int:1..100', 'quantity');

// Expected types with constraints
Validators::is($value, 'string:10');      // exactly 10 bytes
Validators::is($value, 'unicode:5..20');   // 5-20 UTF-8 characters
Validators::is($value, 'int:10..');        // integer >= 10
Validators::is($value, 'array:..50');      // array with max 50 items
```

**Use case:** Configuration validation, user input validation, dynamic type checking where type is stored as string.

### Finder - File and Directory Search

**[Nette\Utils\Finder](src/Utils/Finder.php)** - Powerful tool for searching files and directories

```php
use Nette\Utils\Finder;

// Find files by mask
foreach (Finder::findFiles(['*.txt', '*.md'])->from('src') as $file) {
    echo $file;  // $file is FileInfo object
}

// Find directories
Finder::findDirectories('vendor');

// Wildcards
// * - any characters except /
// ** - any characters including / (multi-level)
// ? - single character
// [...] - character class

// Search paths
->in('src')      // search only in src/ (non-recursive)
->from('src')    // search in src/ and subdirectories (recursive)
->from('.')      // search recursively from current dir

// Filters
->exclude('*.tmp')
->size('>', 1024)
->date('>', '2020-01-01')
->descentFilter(fn($file) => ...)  // filter during traversal
```

**Important breaking changes in 4.0:**
- Case-sensitive by default on Linux (was case-insensitive)
- Paths starting with `/` are absolute (use `./` for relative)
- `filter()` behavior unified - use `descentFilter()` for traversal filtering
- No longer implements `Countable`
- Throws `InvalidStateException` if search directory doesn't exist

### HTML - XSS-Safe HTML Generation

**[Nette\Utils\Html](src/Utils/Html.php)** - Object-oriented HTML element builder with automatic escaping

```php
use Nette\Utils\Html;

// Create elements
$el = Html::el('img')->src('photo.jpg')->alt('Photo');
// <img src="photo.jpg" alt="Photo">

// Multiple ways to set attributes
$el->src = 'image.jpg';           // property
$el->src('image.jpg');             // method (chainable)
$el->setAttribute('src', 'image.jpg');

// Boolean attributes
$el->checked = true;  // <input checked>
$el->checked = false; // <input> (attribute removed)

// Add content (automatically escaped)
$el = Html::el('p')->setText('User input: ' . $userText);

// Add HTML content (NOT escaped)
$el->setHtml('<strong>Bold</strong>');

// Add children
$el->addHtml(Html::el('span')->setText('safe'));
```

**Key feature:** Automatic XSS protection - all text content is escaped by default.

### FileSystem - Exception-Based File Operations

**[Nette\Utils\FileSystem](src/Utils/FileSystem.php)** - File operations that throw exceptions on errors

Unlike native PHP functions that return false, FileSystem methods throw `IOException` exceptions:

```php
use Nette\Utils\FileSystem;

FileSystem::read($file);           // throws IOException on error
FileSystem::write($file, $content);
FileSystem::copy($src, $dest);
FileSystem::delete($path);         // deletes file or entire directory
FileSystem::createDir($path);      // creates including parents
FileSystem::rename($old, $new);
```

**Advantage:** No need to check return values - exceptions ensure errors are handled.

### Strings - UTF-8 String Functions

**[Nette\Utils\Strings](src/Utils/Strings.php)** - UTF-8 aware string manipulation

```php
use Nette\Utils\Strings;

// Case manipulation (requires mbstring)
Strings::lower($s);
Strings::upper($s);
Strings::firstUpper($s);
Strings::capitalize($s);

// String editing
Strings::normalize($s);     // NFC normalization, trim, newlines
Strings::webalize($s);      // 'žluťoučký kůň' -> 'zlutoucky-kun' (requires intl)
Strings::truncate($s, 20);  // preserves whole words
Strings::trim($s);          // UTF-8 aware

// PCRE wrappers with exceptions
Strings::match($s, $pattern);
Strings::matchAll($s, $pattern);
Strings::replace($s, $pattern, $replacement);
Strings::split($s, $pattern);
```

**Important:** Many methods require `mbstring` extension. `webalize()` requires `intl`.

## Important Version 4.0 Changes

### Type Class Replaces Reflection Methods

**Removed in 4.0:**
- `Reflection::getParameterType()`
- `Reflection::getPropertyType()`
- `Reflection::getReturnType()`

**Use instead:** `Type::fromReflection()` - works correctly with union, intersection, and DNF types.

### Finder Breaking Changes

- **Case sensitivity:** Now case-sensitive by default on Linux (was case-insensitive)
- **Absolute paths:** `/pattern` is absolute path; use `./pattern` for relative from current dir
- **Filter methods:** `filter()` now always works the same; use `descentFilter()` for filtering during directory traversal
- **Removed Countable:** Finder no longer implements `Countable` interface
- **Exceptions:** Throws `InvalidStateException` (not `UnexpectedValueException`) when search directory doesn't exist

### Other Changes

- `Html::$xhtml` variable removed
- `Reflection::getParameterDefaultValue()` deprecated (use native `ReflectionParameter::getDefaultValue()`)

## Common Patterns

### Exception Handling

All utility classes throw specific exceptions (never return false/null on errors):

```php
try {
    FileSystem::read($file);
} catch (Nette\FileNotFoundException $e) {
    // Handle missing file
} catch (Nette\IOException $e) {
    // Handle other file errors
}

try {
    $value = Arrays::get($array, 'key');
} catch (Nette\InvalidArgumentException $e) {
    // Handle missing key
}
```

### Working with Arrays/Iterables

```php
// Transformation
$result = Arrays::map($data, fn($item) => $item->value);
$result = Arrays::filter($data, fn($item) => $item->active);

// For iterators (memory efficient)
$result = Iterables::map($iterator, fn($item) => $item->value);  // returns Generator

// Association - transform to associative array
$byId = Arrays::associate($users, 'id');
$byId = Arrays::associate($users, 'id=name');  // ['id' => 'name']
```

### Safe HTML Generation

```php
// Build complex HTML safely
$form = Html::el('form')
    ->method('post')
    ->addHtml(
        Html::el('input')->type('text')->name('email')
    )
    ->addHtml(
        Html::el('button')
            ->type('submit')
            ->setText('Submit')  // auto-escaped
    );

echo $form;  // renders complete HTML
```
