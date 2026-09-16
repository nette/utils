# Upgrading nette/utils

## 4.1.5

- **Renamed** `Nette\Utils\Html::fromHtml()` → `Nette\Utils\Html::html()`; the old name is silently deprecated (5925b5e9)
- **Renamed** `Nette\Utils\Html::fromText()` → `Nette\Utils\Html::text()`; the old name is silently deprecated (5925b5e9)
- **Deprecated** the instance calls `$el->text(...)` and `$el->html(...)` of `Nette\Utils\Html`, previously understood as generic attribute setters; use `Nette\Utils\Html::setText()` or `Nette\Utils\Html::setHtml()` for content and `Nette\Utils\Html::setAttribute()` for a real `text`/`html` attribute; using the old form triggers `E_USER_DEPRECATED` (5925b5e9)
- **Deprecated** absolute masks and masks starting with `../` in `Nette\Utils\Finder::exclude()`: pass a mask relative to the searched directory; using the old form triggers `E_USER_DEPRECATED` (0185cea7)
- **Changed** `Nette\Utils\Finder::exclude()` now reads a trailing `/**` with the `find()` meaning "everything inside the directory", keeping the directory itself; previously it behaved like a trailing `/` and excluded the directory (0185cea7)
- **Changed** `Nette\Utils\Finder::find()` now limits a mask ending with `/` to directories; previously every mask was applied to files too and such a mask threw `Nette\InvalidArgumentException` (4528ff5e)
- **Changed** `Nette\Utils\Finder` masks: a leading `**/` is no longer stripped and a `**` not followed by a slash expands to `**/*`, so `**/foo` combined with `in()` searches recursively and `test/**` means `test/**/*`; previously such masks degraded to a single `*` (c4b697e4)
- **Changed** `Nette\Utils\Finder` no longer normalizes path separators: paths passed to `in()`, `from()` and `append()` are kept verbatim and the pathnames of the yielded `Nette\Utils\FileInfo` keep native separators; previously backslashes were converted to `/` (74d2bf37)

## 4.1.3

- **Signature** `Nette\Utils\Html::offsetGet()`: the return type narrowed from `Nette\HtmlStringable|string` to `Nette\Utils\Html|string`; reading a child that was inserted by `addHtml()` and is not an `Nette\Utils\Html` instance now raises a `TypeError` (0125deec)
- **Signature** `Nette\Utils\Type::resolve()`: the `$of` parameter no longer accepts any `\ReflectionFunctionAbstract`, only `\ReflectionFunction|\ReflectionMethod|\ReflectionParameter|\ReflectionProperty` (ac9e1f00)
- **Signature** `Nette\Utils\Callback::unwrap()`: the return type widened to `callable|array|string` (`callable|array` since 3.2.6); callers must also handle a plain string (ac9e1f00)
- **Signature** native parameter types were added to `Nette\Utils\Arrays::associate()`, `Nette\Utils\Arrays::searchKey()`, `Nette\Utils\Image::calculateSize()` and `Nette\Utils\Validators::assertField()`; a value of another type, previously accepted, now raises a `TypeError` (0125deec)
- **Changed** `Nette\Utils\Image::resize()` in the `Nette\Utils\Image::Cover` mode now throws `Nette\InvalidArgumentException` when `$width` or `$height` is missing; previously it failed with a `TypeError` inside `crop()` (ac9e1f00)
- **Changed** `Nette\Utils\Image::resolveColor()` now reads an array color by the keys `red`, `green`, `blue` and the optional `alpha`; a positional array no longer works and raises a `TypeError` (19e09bda)

## 4.1.1

- **Signature** `Nette\Utils\ArrayHash::offsetGet()`: declares the `mixed` return type; affects subclasses overriding it (c99059c0)

## 4.1.0

- **Removed** `Nette\StaticClass::__callStatic()`: calling an undefined static method now produces the standard PHP error instead of `Nette\MemberAccessException` with a hint (6b03d79b)
- **Deprecated** `Nette\Utils\Strings::TRIM_CHARACTERS` (alias since 4.0.0): use `Nette\Utils\Strings::TrimCharacters`; the constant now carries `#[\Deprecated]`, so on PHP 8.4+ using the old name triggers `E_USER_DEPRECATED` (8f7f5fec)
- **Deprecated** `Nette\Utils\Reflection::isClassKeyword()` (deprecated since 4.0.0): use `Nette\Utils\Validators::isClassKeyword()`; the method now carries `#[\Deprecated]`, so on PHP 8.4+ using it triggers `E_USER_DEPRECATED` (8f7f5fec)
- **Deprecated** `Nette\Utils\Type::isSingle()` (deprecated since 3.2.9): use `Nette\Utils\Type::isSimple()`; the method now carries `#[\Deprecated]`, so on PHP 8.4+ using it triggers `E_USER_DEPRECATED` (8f7f5fec)
- **Signature** `Nette\Utils\Html::addHtml()`: the `$child` parameter narrowed from `mixed` to `Nette\HtmlStringable|string`; a value of another type now raises a `TypeError` instead of being cast (fa1f0b82)
- **Signature** `Nette\Utils\Html::addText()`: the `$text` parameter narrowed from `mixed` to `\Stringable|string`; passing an `int` or `null` raises a `TypeError` until 4.1.1, which accepts them again (fa1f0b82)
- **Changed** `Nette\Utils\DateTime::__construct()` now throws `\Exception` for an invalid date/time string; previously it triggered `E_USER_WARNING` and continued (71fbd2f8)
- **Changed** `Nette\Utils\DateTime::modify()` now throws `\Exception` for an invalid modifier string; previously it triggered `E_USER_WARNING` and continued (71fbd2f8)
- **Changed** `Nette\Utils\ArrayList` no longer uses `Nette\SmartObject`; reading an undefined property or calling an undefined method no longer throws `Nette\MemberAccessException` with a hint, standard PHP behavior applies (542a6f46)
- **Changed** `Nette\Utils\DateTime` no longer uses `Nette\SmartObject`; reading an undefined property or calling an undefined method no longer throws `Nette\MemberAccessException` with a hint (542a6f46)
- **Changed** `Nette\Utils\Finder` no longer uses `Nette\SmartObject`; reading an undefined property or calling an undefined method no longer throws `Nette\MemberAccessException` with a hint (542a6f46)
- **Changed** `Nette\Utils\Html` no longer uses `Nette\SmartObject`; reading an undefined property or calling an undefined method no longer throws `Nette\MemberAccessException` with a hint (542a6f46)

## 4.0.10

- **Renamed** `$subtype` of `Nette\Utils\Type::allows()` → `$type`; affects named-argument calls; the old name was removed (70082cda)

## 4.0.8

- **Replaced** `Nette\Utils\Image::__sleep()` with `Nette\Utils\Image::__serialize()`: a subclass overriding `__sleep()` must override `__serialize()` instead, which returns the data array rather than a list of property names (c930ca4e)
- **Changed** `Nette\Utils\Strings::TrimCharacters` now also contains the line separator and the ideographic space, so `Nette\Utils\Strings::trim()` and the methods using that default set strip them too (2b4c9adb)
- **Changed** `Nette\Utils\DateTime::relativeToSeconds()` now triggers `E_USER_WARNING` for an invalid relative time string; previously it silently returned an incorrect result (aa62311d)
- **Changed** `Nette\Utils\Reflection::getParameterDefaultValue()` is no longer deprecated (deprecated since 4.0.0); the advice to switch to the native `getDefaultValue()` of `\ReflectionParameter` no longer applies, because it behaves differently in some cases (c712d7c3)

## 4.0.7

- **Renamed** `$time` of `Nette\Utils\DateTime::createFromFormat()` → `$datetime`; affects named-argument calls; the old name was removed (54042c67)
- **Changed** `Nette\Utils\DateTime::setDate()` now triggers `E_USER_WARNING` for an out-of-range date; previously it silently rolled over (d7b2b11a)
- **Changed** `Nette\Utils\DateTime::setTime()` now triggers `E_USER_WARNING` for an out-of-range time; previously it silently rolled over (d7b2b11a)
- **Changed** `Nette\Utils\DateTime::__construct()` and `Nette\Utils\DateTime::modify()` now trigger `E_USER_WARNING` when the PHP date parser reports an error or an overflow; previously it was ignored (d7b2b11a)

## 4.0.6

- **Changed** `Nette\Utils\Iterables::toIterator()` now throws `Nette\ShouldNotHappenException` for an iterable that is neither `\Iterator`, `\IteratorAggregate` nor an array; previously `\UnhandledMatchError` (f6952629)

## 4.0.5

- **Renamed** `$iterator` of `Nette\Iterators\CachingIterator::__construct()` → `$iterable`; affects named-argument calls; the old name was removed (31f46844)
- **Deprecated** `Nette\Iterators\Mapper`: use `Nette\Utils\Iterables::map()`, a static function over any iterable instead of a wrapper object, so the call has to be rewritten; the class is silently deprecated (368322d2)
- **Changed** `Nette\Iterators\CachingIterator::__construct()` now throws a `TypeError` for an unsupported argument type; previously `Nette\InvalidArgumentException` (31f46844)

## 4.0.4

- **Renamed** `$callback` of `Nette\Utils\Arrays::every()` → `$predicate`; affects named-argument calls; the old name was removed (662e5247)
- **Renamed** `$callback` of `Nette\Utils\Arrays::map()` → `$transformer`; affects named-argument calls; the old name was removed (662e5247)
- **Renamed** `$callback` of `Nette\Utils\Arrays::some()` → `$predicate`; affects named-argument calls; the old name was removed (662e5247)
- **Changed** `Nette\Utils\Strings::TrimCharacters` now also contains further Unicode spaces and the zero-width space, so `Nette\Utils\Strings::trim()` and the methods using that default set strip them too (74618ab5)

## 4.0.3

- **Deprecated** `Nette\Utils\Image::rgb()`: use `Nette\Utils\ImageColor::rgb()`, which returns an object instead of an array and takes opacity as a float 0..1 instead of transparency 0..127; the old name is silently deprecated (2790367a)
- **Changed** `Nette\Utils\Type::resolve()` now resolves `static` to the originally called class for reflection obtained through `Nette\Utils\Callback::toReflection()`; previously to the declaring class (835fb3c2)

## 4.0.2

- **Signature** `Nette\StaticClass::__construct()`: the constructor is now `private` and no longer throws `\Error` itself; instantiating such a class is rejected by PHP and reflection reports the class as not instantiable (dd7502e7)
- **Changed** `Nette\Utils\Strings::length()` now tests for the mbstring extension itself and falls back to `iconv_strlen()`; previously it tested for the `mb_strlen()` function, so the result may differ where that function came only from a polyfill (380966d6)

## 4.0.1

- **Changed** `Nette\Utils\Finder::collect()` now returns a list with integer keys; previously an array keyed by pathname (8d80a2df)
- **Changed** `Nette\Utils\Finder::in()` and `Nette\Utils\Finder::from()` now throw `Nette\InvalidStateException` when a wildcard base directory matches nothing; previously the result was silently empty (0ce0601f)

## 4.0.0

- **Removed** `Nette\Utils\Callback::closure()` (deprecated since 3.0.0): call `\Closure::fromCallable($callable)` (f9136650)
- **Removed** `Nette\Utils\Callback::invoke()` (deprecated since 3.0.0): call the callable directly as `$callable(...$args)` (f9136650)
- **Removed** `Nette\Utils\Callback::invokeArgs()` (deprecated since 3.0.0): call the callable directly as `$callable(...$args)` (f9136650)
- **Removed** `Nette\Utils\ObjectMixin` (deprecated since 3.0.0): use `Nette\Utils\ObjectHelpers` (f9136650)
- **Removed** `Nette\Utils\Reflection::getParameterType()` (deprecated since 3.2.10): use `Nette\Utils\Type::fromReflection()`, which returns a `Nette\Utils\Type` object instead of a string (f9136650)
- **Removed** `Nette\Utils\Reflection::getPropertyType()` (deprecated since 3.2.10): use `Nette\Utils\Type::fromReflection()` (f9136650)
- **Removed** `Nette\Utils\Reflection::getReturnType()` (deprecated since 3.2.10): use `Nette\Utils\Type::fromReflection()` (f9136650)
- **Removed** `Nette\Utils\Reflection::getParameterTypes()` (deprecated since 3.1.6): use `Nette\Utils\Type::fromReflection()` and its `getNames()` (f9136650)
- **Removed** `Nette\Utils\Reflection::getPropertyTypes()` (deprecated since 3.1.6): use `Nette\Utils\Type::fromReflection()` and its `getNames()` (f9136650)
- **Removed** `Nette\Utils\Reflection::getReturnTypes()` (deprecated since 3.1.6): use `Nette\Utils\Type::fromReflection()` and its `getNames()` (f9136650)
- **Removed** `Nette\Utils\RegexpException::MESSAGES`: use the native `preg_last_error_msg()` (6830fc22)
- **Removed** `Nette\Utils\Html::$xhtml`: the XHTML rendering mode is gone, output is always rendered HTML5-style, so empty elements are no longer self-closed, boolean attributes are no longer quoted and `<` in an attribute value is no longer escaped (3d182a00)
- **Removed** `Nette\Utils\Finder::count()`: `Nette\Utils\Finder` no longer implements `\Countable`; count the array returned by `collect()` (f9136650)
- **Removed** `Nette\Utils\Finder::extensionMethod()`: extension methods are no longer supported (f9136650)
- **Renamed** `Nette\Utils\ObjectMixin::getSuggestion()` → `Nette\Utils\ObjectHelpers::getSuggestion()` (deprecated since 3.0.0); the old name was removed (f9136650)
- **Renamed** `Nette\Utils\Finder::compare()` → `Nette\Utils\Helpers::compare()`; the old name was removed (6e7b3b02)
- **Renamed** `Nette\Utils\Image::EMPTY_GIF` → `Nette\Utils\Image::EmptyGIF`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Image::EXACT` → `Nette\Utils\Image::Cover`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Image::FILL` → `Nette\Utils\Image::OrBigger`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Image::FIT` → `Nette\Utils\Image::OrSmaller`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Image::SHRINK_ONLY` → `Nette\Utils\Image::ShrinkOnly`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Image::STRETCH` → `Nette\Utils\Image::Stretch`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Strings::TRIM_CHARACTERS` → `Nette\Utils\Strings::TrimCharacters`; the old name still works as an alias (6adaf795)
- **Renamed** `Nette\Utils\Strings::normalizeNewLines()` → `Nette\Utils\Strings::unixNewLines()`; the old name still works as an alias (d21685e4)
- **Renamed** `Nette\Utils\Strings::checkEncoding()` → `Nette\Utils\Validators::isUnicode()`; the old name is silently deprecated (5c743dec)
- **Renamed** `Nette\Utils\Strings::contains()` → `str_contains()`; the old name is silently deprecated (5c743dec)
- **Renamed** `Nette\Utils\Strings::endsWith()` → `str_ends_with()`; the old name is silently deprecated (5c743dec)
- **Renamed** `Nette\Utils\Strings::startsWith()` → `str_starts_with()`; the old name is silently deprecated (5c743dec)
- **Renamed** `$flags` of `Nette\Utils\Image::resize()` → `$mode`; affects named-argument calls; the old name was removed (6adaf795)
- **Renamed** `$flags` of `Nette\Utils\Image::calculateSize()` → `$mode`; affects named-argument calls; the old name was removed (6adaf795)
- **Renamed** `$flags` of `Nette\Utils\Arrays::grep()` → `$invert`; pass `true` instead of `PREG_GREP_INVERT`; the old name was removed (c6fd05c8)
- **Deprecated** `Nette\Utils\Json::ESCAPE_UNICODE`: pass `asciiSafe: true` to `Nette\Utils\Json::encode()`; the constant is silently deprecated (c6fd05c8)
- **Deprecated** `Nette\Utils\Json::FORCE_ARRAY`: pass `forceArrays: true` to `Nette\Utils\Json::decode()`; the constant is silently deprecated (c6fd05c8)
- **Deprecated** `Nette\Utils\Json::PRETTY`: pass `pretty: true` to `Nette\Utils\Json::encode()`; the constant is silently deprecated (c6fd05c8)
- **Deprecated** `Nette\Utils\Reflection::isBuiltinType()` (alias since 3.2.9): use `Nette\Utils\Validators::isBuiltinType()`; the old name is silently deprecated (5c743dec)
- **Deprecated** `Nette\Utils\Reflection::isClassKeyword()` (alias since 3.2.9): use `Nette\Utils\Validators::isClassKeyword()`; the old name is silently deprecated (5c743dec)
- **Deprecated** `Nette\Utils\Reflection::getParameterDefaultValue()`: call the native `getDefaultValue()` of `\ReflectionParameter`; the old name is silently deprecated (5c743dec)
- **Deprecated** `Nette\Utils\Validators::isType()`: it is silently deprecated (5c743dec)
- **Signature** `Nette\Utils\Json::decode()`: the `int $flags` parameter was replaced by `bool $forceArrays`; a bit combination other than the former `Nette\Utils\Json::FORCE_ARRAY` no longer works (c6fd05c8)
- **Signature** `Nette\Utils\Json::encode()`: the `int $flags` parameter was replaced by `bool $pretty` and `bool $asciiSafe`; a bit combination other than the former `Nette\Utils\Json::PRETTY` and `Nette\Utils\Json::ESCAPE_UNICODE` no longer works (c6fd05c8)
- **Signature** `Nette\Utils\Strings::match()`: the `int $flags` parameter was replaced by `bool $captureOffset` and `bool $unmatchedAsNull`; other `PREG_*` flags can no longer be combined directly (c6fd05c8)
- **Signature** `Nette\Utils\Strings::matchAll()`: the `int $flags` parameter was replaced by `bool $captureOffset`, `bool $unmatchedAsNull` and `bool $patternOrder`; other `PREG_*` flags can no longer be combined directly (c6fd05c8)
- **Signature** `Nette\Utils\Strings::split()`: the `int $flags` parameter was replaced by `bool $captureOffset` and `bool $skipEmpty`; other `PREG_SPLIT_*` flags can no longer be combined directly (c6fd05c8)
- **Signature** `Nette\Utils\ArrayHash::getIterator()`: returns a one-shot generator instead of a rewindable `\RecursiveArrayIterator`, so recursive iteration of nested arrays and repeated rewinds no longer work (cacdbf5a)
- **Signature** `Nette\Utils\ArrayList::getIterator()`: returns a one-shot generator by reference instead of a rewindable `\ArrayIterator`, so iterating twice or using `\ArrayIterator` methods no longer works (0bb0999b)
- **Signature** `Nette\Localization\Translator::translate()`: takes `string|\Stringable $message` and returns `string|\Stringable`; affects implementations of the interface (53c205ed)
- **Signature** `Nette\Utils\Html::href()`: `$query` defaults to `[]` and no longer accepts `null`; passing `null` explicitly throws a `TypeError`, omit the argument instead (41f28244)
- **Signature** `Nette\Utils\Html::insert()`: the `$child` parameter is typed `Nette\HtmlStringable|string`; a value of another type, previously cast to string, now throws a `TypeError` (4d468663)
- **Signature** `Nette\Utils\Finder::find()`, `Nette\Utils\Finder::findFiles()`, `Nette\Utils\Finder::findDirectories()`, `Nette\Utils\Finder::in()`, `Nette\Utils\Finder::from()` and `Nette\Utils\Finder::exclude()` take a single `string|array` argument instead of variadic masks or paths (7828eba3)
- **Signature** native parameter, return and property types were added throughout the package, notably to `Nette\Utils\Arrays`, `Nette\Utils\Html`, `Nette\Utils\Image`, `Nette\Utils\DateTime`, `Nette\Utils\Paginator` and `Nette\Utils\Validators`; a value of another type, previously coerced, now throws a `TypeError` (4d468663, 9445f39e)
- **Changed** `Nette\Utils\JsonException` now extends PHP's own JSON exception instead of `\Exception`, so a `catch (\JsonException)` that previously did not match it now does (fcd3fcc7)
- **Changed** `Nette\Utils\DateTime::createFromFormat()` now throws a `TypeError` when `$timezone` is neither a string nor a `\DateTimeZone`; previously `Nette\InvalidArgumentException` (4d468663)
- **Changed** `Nette\Utils\Finder` now iterates over `Nette\Utils\FileInfo` instances and passes them to the `filter()` and `exclude()` callbacks; previously `\SplFileInfo` from a recursive directory iterator; use `getRelativePath()` and `getRelativePathname()` instead of `getSubPath()` and `getSubPathName()` (58eb1e80)
- **Changed** `Nette\Utils\Finder::filter()`, `Nette\Utils\Finder::size()`, `Nette\Utils\Finder::date()` and `Nette\Utils\Finder::exclude()` now apply to every matched entry regardless of the chaining order; previously each `find()` mask formed its own filter group (58eb1e80)
- **Changed** `Nette\Utils\Finder::exclude()` mask semantics: a trailing `/`, `/*` or `/**` now decides whether only the descent into a directory is skipped or matched entries are filtered out too; previously the mask only skipped the descent (58eb1e80)
- **Changed** `Nette\Utils\Finder` now silently skips an unreadable directory; previously the iterator let an exception escape that only a `catch (\UnexpectedValueException)` caught; call `ignoreUnreadableDirs(false)` to get an exception again, now `Nette\InvalidStateException` (d422dd05)
- **Changed** `Nette\Utils\Finder::findFiles()` and `Nette\Utils\Finder::files()` now throw `Nette\InvalidArgumentException` for a mask ending with `/`; previously it was silently accepted (d51381f6)

## 3.2.10

- **Deprecated** `Nette\Utils\Reflection::getParameterType()`: use `Nette\Utils\Type::fromReflection()`, which returns a `Nette\Utils\Type` object instead of a string, so call e.g. `getSingleName()` on it; the old name is silently deprecated (d838929f)
- **Deprecated** `Nette\Utils\Reflection::getPropertyType()`: use `Nette\Utils\Type::fromReflection()`; the old name is silently deprecated (d838929f)
- **Deprecated** `Nette\Utils\Reflection::getReturnType()`: use `Nette\Utils\Type::fromReflection()`; the old name is silently deprecated (d838929f)
- **Changed** `Nette\Utils\Callback::unwrap()` now returns the real declaring class for a closure created from a private method; previously the class of `$this` when it differed (07c00069)

## 3.2.9

- **Renamed** `Nette\Utils\Reflection::isBuiltinType()` → `Nette\Utils\Validators::isBuiltinType()`; the old name still works as an alias (68efe596)
- **Renamed** `Nette\Utils\Reflection::isClassKeyword()` → `Nette\Utils\Validators::isClassKeyword()`; the old name still works as an alias (68efe596)
- **Renamed** `Nette\Utils\Type::isSingle()` → `Nette\Utils\Type::isSimple()`; the old name is silently deprecated (912db4a8)
- **Renamed** `$type` of `Nette\Utils\Type::allows()` → `$subtype`; affects named-argument calls; the old name was removed (3dabec59)
- **Renamed** `$reflection` of `Nette\Utils\Type::resolve()` → `$of`; affects named-argument calls; the old name was removed (3dabec59)
- **Changed** `Nette\Utils\Type::getNames()` may now return nested arrays for a disjunctive normal form type; previously always a flat list of strings (c91bac34)
- **Changed** `Nette\Utils\Type::allows()` was rewritten for disjunctive normal form types; the result for edge combinations of union and intersection types may differ (c91bac34)

## 3.2.7

- **Changed** `Nette\Utils\Json::FORCE_ARRAY`, `Nette\Utils\Json::PRETTY` and `Nette\Utils\Json::ESCAPE_UNICODE` now hold the native `JSON_*` values instead of small bit flags; code that stored or compared the numeric values must be updated (50f2a545)
- **Changed** `Nette\Utils\Json::decode()` now passes `$flags` straight to the native `json_decode()`; previously only the `Nette\Utils\Json::FORCE_ARRAY` bit was honored and the rest was ignored (50f2a545)
- **Changed** `Nette\Utils\Json::encode()` now passes `$flags`, except the `Nette\Utils\Json::ESCAPE_UNICODE` bit, straight to the native `json_encode()`; previously only the pretty and escape bits were honored, so custom bit combinations may behave differently (50f2a545)
- **Changed** `Nette\Utils\Image::fromFile()` now throws `Nette\Utils\ImageException` only on a real failure; a GD warning on an otherwise successful load triggers `E_USER_WARNING` instead (0bcbb94f)
- **Changed** `Nette\Utils\Validators::isNumeric()` now returns true for strings such as `1.` and `.5`; previously false (e705c5a7)

## 3.2.6

- **Signature** `Nette\Utils\Callback::unwrap()`: the return type widened from `callable` to `callable|array`; code that assumed a callable must now handle a plain array too (2bd8bafc)

## 3.2.5

- **Changed** `Nette\Utils\Type::isUnion()` now returns true only for a union; for an intersection type it returns false and `isIntersection()` is the new test; previously it returned true for any compound type (9cd80396)
- **Changed** `Nette\Utils\Type::fromReflection()` returns the tentative return type on PHP 8.1 when the reflected method has no declared return type; previously it returned null (f168ed92)

## 3.2.2

- **Changed** `Nette\Utils\DateTime::fromParts()` formats the fractional second locale-independently; in a locale with a comma decimal separator the same call previously produced an invalid date string (dc14007c)

## 3.2.0

- **Renamed** `Nette\Localization\ITranslator` → `Nette\Localization\Translator`; the old name still works as an alias (10e8d627)
- **Renamed** `Nette\Utils\IHtmlString` → `Nette\HtmlStringable`; the old name still works as an alias (412039b5)
- **Renamed** `Nette\Utils\Arrays::searchKey()` → `Nette\Utils\Arrays::getKeyOffset()`; the old name is silently deprecated (d0427c18)
- **Renamed** `$detectedFormat` of `Nette\Utils\Image::fromFile()` → `$type`; positional calls are unaffected, a named-argument call must be updated; the old name was removed (bb5c632c)
- **Renamed** `$detectedFormat` of `Nette\Utils\Image::fromString()` → `$type`; positional calls are unaffected, a named-argument call must be updated; the old name was removed (bb5c632c)
- **Deprecated** `Nette\Utils\Callback::closure()` (deprecated since 3.0.0): call `\Closure::fromCallable($callable)`; using the old name triggers `E_USER_DEPRECATED` (8a4b795a)
- **Changed** `Nette\Utils\Reflection::toString()` now appends `()` after the name of a method or function and no longer appends it after the enclosing function name of a parameter (36f531a4)

## 3.1.6

- **Deprecated** `Nette\Utils\Reflection::getParameterTypes()`: use `Nette\Utils\Type::fromReflection()` and its `getNames()`; the old name is silently deprecated; also in 3.2.5 (09890e63)
- **Deprecated** `Nette\Utils\Reflection::getPropertyTypes()`: use `Nette\Utils\Type::fromReflection()` and its `getNames()`; the old name is silently deprecated; also in 3.2.5 (09890e63)
- **Deprecated** `Nette\Utils\Reflection::getReturnTypes()`: use `Nette\Utils\Type::fromReflection()` and its `getNames()`; the old name is silently deprecated; also in 3.2.5 (09890e63)
- **Signature** `Nette\Utils\Strings::replace()`: the default of `$replacement` changed from `null` to an empty string; code that relied on the default being `null` must be updated; also in 3.2.1 (74071061)
- **Changed** `Nette\Utils\Strings::replace()` treats an array `$pattern` with string keys as a pattern-to-replacement map whenever it has string keys; previously only when `$replacement` was left at its default; also in 3.2.1 (74071061)

## 3.1.3

- **Removed** `Nette\Utils\Image::dashedLine()`: the underlying GD function is deprecated in PHP; set a style with `setStyle()` and draw with `line()` (a65ab187)
- **Renamed** `Nette\Utils\Validators::isList()` → `Nette\Utils\Arrays::isList()`; the old name is silently deprecated (2ce1c5cb)
- **Renamed** `$arr` of `Nette\Utils\ArrayHash::from()` → `$array`; affects named-argument calls; the old name was removed (6e8ca53b)
- **Signature** `Nette\Utils\Arrays::renameKey()`: returns `bool` telling whether the old key was found; previously `void` (c09937fb)
- **Changed** `Nette\Utils\Reflection::expandClassName()` now expands `static` to the context class name like `self`; previously it passed through as an unknown class name (875e5c88)

## 3.1.1

- **Changed** `Nette\Utils\Html::htmlToText()` now also decodes the `&apos;` entity; `Nette\Utils\Html::getText()` is affected the same way, because it delegates to it (1ac2e667)

## 3.1.0

- **Changed** `Nette\Utils\Strings::toAscii()` now throws `Nette\Utils\RegexpException` on malformed UTF-8 input; previously it silently returned corrupted output (1fbb8e14)
- **Changed** `Nette\Utils\Strings::webalize()` now throws `Nette\Utils\RegexpException` on malformed UTF-8 input or an invalid `$charlist`; previously it silently returned corrupted output (368a29f1)
- **Changed** `Nette\Utils\Validators::is()` now throws `Nette\Utils\RegexpException` for an invalid regular expression of the `pattern` type; previously it returned false (368a29f1)
- **Changed** `Nette\Utils\Strings::toAscii()` transliterates the Cyrillic я and ю as `ya` and `yu`; previously the result depended on the iconv fallback (d6cd63d7)

## 3.0.4

- **Changed** `Nette\Utils\Image::calculateSize()` now throws `Nette\InvalidArgumentException` for a dimension that is not an int, a numeric string or a percent string; previously such a value was silently cast to int; affects `Nette\Utils\Image::resize()`, which calls it; also in 3.1.2 (831cd10e)
- **Changed** `Nette\Utils\Image::calculateCutout()` now throws `Nette\InvalidArgumentException` for a dimension that is not an int, a numeric string or a percent string; previously such a value was silently cast to int; affects `Nette\Utils\Image::crop()`, which calls it; also in 3.1.2 (831cd10e)
- **Changed** `Nette\Utils\Image::place()` now throws `Nette\InvalidArgumentException` when `$left` or `$top` is not an int, a numeric string or a percent string; previously such a value was silently cast to int; also in 3.1.2 (831cd10e)
- **Changed** `Nette\Utils\Image::fromString()` now always detects the image type and throws `Nette\Utils\UnknownImageFileException` for an unknown one; previously the type was detected only when `$detectedFormat` was passed by reference; also in 3.1.4 (0dd92249)
- **Changed** `Nette\Utils\Image::getImageResource()` returns a `\GdImage` object on PHP 8.0+; code testing the result with `is_resource()` must test `instanceof \GdImage` as well (65007027)

## 3.0.2

- **Signature** `Nette\Utils\Html::insert()`: `$index` lost its default value and can no longer be omitted, not even through a named argument (2330b6a6)
- **Changed** `Nette\Utils\Html::__toString()` on PHP 7.4+ rethrows an exception raised while rendering; previously every exception was converted to `E_USER_ERROR` and an empty string was returned (19cd663f)
- **Changed** `Nette\Utils\Image::__toString()` on PHP 7.4+ rethrows an exception raised while converting; previously it was rethrown only when the method was called with an argument (19cd663f)
- **Changed** `Nette\Utils\Validators::isUrl()` now accepts a URL with a query string or a fragment; previously it rejected them (56217711)

## 3.0.1

- **Changed** `Nette\Utils\Reflection::getUseStatements()` now throws `Nette\NotImplementedException` for an anonymous class; previously it proceeded (0b710172)
- **Changed** `Nette\Utils\Validators::is()` now catches a `TypeError` thrown by a registered validator for an incompatible value and treats it as no match; previously it propagated out (b1fa4006)
- **Changed** `Nette\Utils\Validators::isNumeric()` now returns true for a string with a leading `+`; previously only a leading `-` was accepted (e93dd1ad)
- **Changed** `Nette\Utils\Validators::isNumericInt()` now returns true for a string with a leading `+`; previously only a leading `-` was accepted (e93dd1ad)

## 3.0.0

- **Removed** `Nette\Object`: use the trait `Nette\SmartObject` instead of extending a base class (fd485100)
- **Removed** `Nette\StaticClassException`: `Nette\StaticClass` now throws `\Error` on instantiation; affects code catching it (68d27ff8)
- **Removed** `Nette\SmartObject::getReflection()`: use `\ReflectionClass` directly (d5c2935e)
- **Removed** `Nette\SmartObject::extensionMethod()`: the extension-method mechanism was removed (d5c2935e)
- **Removed** `Nette\Utils\ObjectMixin::getExtensionMethods()`: the extension-method mechanism was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::call()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::callStatic()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::get()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::set()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::has()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::remove()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\ObjectMixin::getMagicMethods()`: the `Nette\Object` mechanism it served was removed (fd485100)
- **Removed** `Nette\Utils\Image::ENLARGE`: it had the same value as `Nette\Utils\Image::FIT`, use that (3753da89)
- **Renamed** `Nette\Iterators\Filter` → `\CallbackFilterIterator`; the old name was removed (3753da89)
- **Renamed** `Nette\Iterators\RecursiveFilter` → `\RecursiveCallbackFilterIterator`; the old name was removed (3753da89)
- **Renamed** `Nette\Utils\Html::add()` → `Nette\Utils\Html::addHtml()`; the old name was removed (3753da89)
- **Renamed** `Nette\Utils\Strings::random()` → `Nette\Utils\Random::generate()`; the old name was removed (3753da89)
- **Renamed** `Nette\Utils\ObjectMixin::strictCall()` → `Nette\Utils\ObjectHelpers::strictCall()`; the old name was removed (fd485100)
- **Renamed** `Nette\Utils\ObjectMixin::strictGet()` → `Nette\Utils\ObjectHelpers::strictGet()`; the old name was removed (fd485100)
- **Renamed** `Nette\Utils\ObjectMixin::strictSet()` → `Nette\Utils\ObjectHelpers::strictSet()`; the old name was removed (fd485100)
- **Renamed** `Nette\Utils\ObjectMixin::strictStaticCall()` → `Nette\Utils\ObjectHelpers::strictStaticCall()`; the old name was removed (fd485100)
- **Renamed** `Nette\Utils\ObjectMixin::getMagicProperties()` → `Nette\Utils\ObjectHelpers::getMagicProperties()`; the replacement is marked `@internal`, unlike the original; the old name was removed (c2d8dc34)
- **Renamed** `Nette\Utils\ObjectMixin::getSuggestion()` → `Nette\Utils\ObjectHelpers::getSuggestion()`; using the old name triggers `E_USER_DEPRECATED` (8bb70ae9)
- **Renamed** `$width` of `Nette\Iterators\CachingIterator::isFirst()` → `$gridWidth`; affects named-argument calls; the old name was removed (1cffa68f)
- **Renamed** `$width` of `Nette\Iterators\CachingIterator::isLast()` → `$gridWidth`; affects named-argument calls; the old name was removed (1cffa68f)
- **Renamed** `$m` of `Nette\Utils\Callback::closure()` → `$method`; affects named-argument calls; the old name was removed (1cffa68f)
- **Deprecated** `Nette\Utils\ObjectMixin`: use `Nette\Utils\ObjectHelpers`; the class is silently deprecated (969656db)
- **Deprecated** `Nette\Utils\Callback::closure()`: call `\Closure::fromCallable($callable)`, and when the optional `$method` argument was used, build the callable as `[$callable, $method]` first; the old name is silently deprecated (0202bded)
- **Deprecated** `Nette\Utils\Callback::invoke()`: call the callable directly as `$callable(...$args)`; using the old name triggers `E_USER_DEPRECATED` (6201e3fe, c0ab1b97)
- **Deprecated** `Nette\Utils\Callback::invokeArgs()`: call the callable directly as `$callable(...$args)`; using the old name triggers `E_USER_DEPRECATED` (6201e3fe, c0ab1b97)
- **Deprecated** `Nette\Utils\ObjectMixin::getExtensionMethod()`: the extension-method mechanism was removed, so the method always behaves as if nothing were registered; using it triggers `E_USER_DEPRECATED` (fd485100)
- **Deprecated** `Nette\Utils\ObjectMixin::setExtensionMethod()`: the extension-method mechanism was removed, so the method no longer registers anything; using it triggers `E_USER_DEPRECATED` (fd485100)
- **Signature** native parameter and return types were added throughout the package, notably to `Nette\Utils\Strings`, `Nette\Utils\Validators`, `Nette\Utils\Arrays`, `Nette\Utils\Html`, `Nette\Utils\Image`, `Nette\Utils\FileSystem`, `Nette\Utils\Paginator` and `Nette\Utils\Reflection`; a value of another type, previously coerced, now throws a `TypeError` (d0041ba5, 8ba25f09)
- **Signature** `Nette\Utils\IHtmlString::__toString()`: declares the `string` return type; an implementation without a compatible declaration is a fatal error (d0041ba5)
- **Signature** `Nette\Localization\ITranslator::translate()`: the second parameter is the variadic `...$parameters` instead of the optional `int $count`, and the return type is `string`; affects implementations of the interface (3c23141b)
- **Signature** `Nette\Utils\ArrayHash::from()`: the first parameter must be a native array; a `\Traversable` is no longer accepted (d0041ba5)
- **Signature** `Nette\Utils\Image::save()`: returns `void` and throws `Nette\Utils\ImageException` on failure; previously it returned a bool (0fddd5d2)
- **Signature** `Nette\Utils\Image::send()`: returns `void` and throws `Nette\Utils\ImageException` on failure; previously it returned a bool (0fddd5d2)
- **Signature** `Nette\Utils\Image::save()`: `$file` is required and no longer accepts `null`; calling it without a file name throws a `TypeError`, use `toString()` to get the image data (a316b52b)
- **Signature** `Nette\Utils\Strings::findPrefix()`: takes a single array of strings instead of variadic string arguments (146d4ca7)
- **Signature** `Nette\Utils\Validators::assertField()`: the first parameter is type-hinted `array`; a non-array now throws a `TypeError` instead of `Nette\Utils\AssertionException` (20663575)
- **Signature** `Nette\Utils\Validators::everyIs()`: the first parameter is type-hinted `iterable`; a non-iterable now throws a `TypeError` instead of the method returning false (841fbf39)
- **Signature** most public methods of `Nette\Utils\Html` are now `final`; affects subclasses overriding them (a632cd64)
- **Changed** `Nette\SmartObject` no longer resolves `$obj->name` to `get<Name>()`, `is<Name>()` or `set<Name>()` without a `@property` annotation; add the annotation or call the accessor method directly (d5c2935e)
- **Changed** `Nette\SmartObject` no longer invokes a closure stored in a public property as `$obj->name(...)`; call it as `($obj->name)(...)` (d5c2935e)
- **Changed** `Nette\SmartObject` no longer returns a public method as a closure when read as `$obj->methodName` (d5c2935e)
- **Changed** `Nette\SmartObject` no longer handles `@method` annotations through `__call()` (d5c2935e)
- **Changed** `Nette\SmartObject` invokes an `$onEvent` handler directly as `$handler(...$args)`, so a non-callable item in the array raises a native `TypeError`; previously `Nette\InvalidArgumentException` (a3d56dd8)
- **Changed** `Nette\MemberAccessException` now extends `\Error` instead of `\LogicException`; affects code catching `\LogicException` or `\Exception`, which no longer matches it (0ef12af2)
- **Changed** `Nette\Utils\Callback` is now `final` and can no longer be extended (a632cd64)
- **Changed** `Nette\Utils\FileSystem` is now `final` and can no longer be extended (a632cd64)
- **Changed** `Nette\Utils\Json` is now `final` and can no longer be extended (a632cd64)
- **Changed** `Nette\Utils\Random` is now `final` and can no longer be extended (a632cd64)
- **Changed** `Nette\Utils\Reflection` is now `final` and can no longer be extended (a632cd64)
- **Changed** `Nette\Utils\Callback::closure()` on PHP 7.1+ uses the native `fromCallable()` of `\Closure`, so it can no longer build a closure for a private or protected method from outside its scope; such a call now throws `Nette\InvalidArgumentException` (98b1e4d7)
- **Changed** `Nette\Utils\Callback::toReflection()` always returns native reflection objects; previously it returned `Nette\Reflection` instances when that package was installed (65c28a4a)
- **Changed** `Nette\Utils\Callback::invokeSafe()` passes an error message without HTML markup to the `$onError` callback; previously the markup was kept when `html_errors` was enabled (28d4e3e4)
- **Changed** `Nette\Utils\DateTime::getTimestamp()` returns a native `int`; previously the override returned `int|string` for timestamps outside the int range (cc287b75)
- **Changed** `Nette\Utils\DateTime::setTimestamp()` accepts only a native `int`; previously the override also accepted a string, which now throws a `TypeError` (cc287b75)
- **Changed** `Nette\Utils\ArrayList` throws `Nette\OutOfRangeException` for any non-integer index; previously the index only went through a numeric comparison without a type check (2422972a)
- **Changed** `Nette\Utils\Arrays::searchKey()` returns `null` instead of `false` when the key is not found (fcad7158)
- **Changed** `Nette\Utils\Strings::after()` returns `null` instead of `false` when the needle is not found (29ba4cb3)
- **Changed** `Nette\Utils\Strings::before()` returns `null` instead of `false` when the needle is not found (29ba4cb3)
- **Changed** `Nette\Utils\Strings::indexOf()` returns `null` instead of `false` when the needle is not found (29ba4cb3)
- **Changed** `Nette\Utils\Strings::normalize()` also converts the string to the Unicode normal form NFC, on top of the previous line-ending and space normalization (6b858f61)
- **Changed** `Nette\Utils\Strings::compare()` normalizes both strings to the Unicode normal form NFD before comparing, so strings differing only in composition now compare equal (6b858f61)
- **Changed** `Nette\Utils\Image` throws `Nette\NotSupportedException` on an attempt to serialize an instance; previously serialization succeeded but produced an unusable result (2227ea78)
- **Changed** `Nette\Utils\Html::setText()`, `Nette\Utils\Html::addText()` and `Nette\Utils\Html::insert()` accept any `Nette\Utils\IHtmlString` instance and insert it without escaping; previously only an `Nette\Utils\Html` instance was treated that way (ab1d0b86)
- **Changed** `Nette\Utils\Paginator::setItemCount()` no longer understands `false` as "unlimited", the new int type hint coerces it to 0; pass `null` for an unlimited item count (d0041ba5)
- **Changed** `Nette\Utils\Validators::isInRange()` returns false when the value is `null`; previously such a value could pass depending on the bounds (b3be7ea7)
- **Changed** `Nette\Utils\Validators::isInRange()` returns false when both bounds are `null`; previously it returned true for any non-null value (0077e0aa)
- **Changed** `Nette\Utils\Validators::isInRange()` compares a bound as a string when the bound is a string and numerically when it is numeric; previously it used a plain comparison (296ca379)
