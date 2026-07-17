# To My Agents!

It is my fervent wish that this file guide every AI coding agent working with code in this repository.

## Documentation

Any distilled, agent-facing documentation for this package - how it works
internally and the rationale behind key design decisions - lives in `docs/`.
Consult it before non-trivial changes; it is the source of truth from which the
public manual is distilled.

Most classes are self-explanatory static helpers, but a handful carry non-obvious
traps (the eager/lazy divide, uneven extension requirements, `Html` attribute
magic, and the two engines: `Finder` and `Process`). Those are in
`docs/internals.md` - read it before touching them.

## Project Overview

Nette Utils is a standalone PHP utility library: strings, arrays/iterables, HTML
generation, images, JSON, validation, filesystem, file search, and process
execution.

- **PHP Version**: 8.2 - 8.5
- **Package**: `nette/utils`

## Essential Commands

```bash
# Run all tests
vendor/bin/tester tests -s        # or: composer tester
vendor/bin/tester tests/Utils/ -s

# Static analysis (informative in CI - does not fail the build)
composer phpstan
```

## Conventions

- Every file starts with `declare(strict_types=1);`; everything typed; `readonly`
  for immutable properties; **tabs**; Nette Coding Standard. Two-letter abbreviations
  are UPPERCASE (`HTML`, `IO`), longer ones PascalCase/camelCase (`Json`, `DateTime`).
- Exceptions live in two collector files: generic ones (`InvalidStateException`,
  `IOException`, ...) in `src/exceptions.php`, utils-specific ones (`ImageException`,
  `JsonException`, `RegexpException`, `ProcessFailedException`, ...) in
  `src/Utils/exceptions.php`; phpDoc adds value beyond
  the signature or is omitted; exception messages describe the problem ("The file
  does not exist.").
- Tests are Nette Tester `.phpt` mirroring `src/Utils/`; use `test()` /
  `testException()` and `getTempDir()`.

## Working in this repo

- **`Arrays` is eager (returns arrays); `Iterables` is lazy (often returns a
  single-use, non-rewindable `Generator`).** Never hand back an `Iterables` result
  that a caller must traverse twice. `Iterators\CachingIterator` does **not** make a
  generator re-iterable - use `Iterables::memoize()` for that.
- **`Strings` assumes valid UTF-8 and needs extensions UNEVENLY.** `lower`/`upper`
  (and therefore `compare`) hard-require `mbstring`; **`toAscii`/`webalize`
  hard-require `intl`** and throw without it (**not** `iconv` - iconv is only used
  opportunistically inside `toAscii`). `normalize`/`compare` degrade gracefully
  without `intl`. (An older docs claim tying `webalize`/`toAscii` to `ext-iconv` is
  wrong.)
- **`Html` sets attributes through magic** (`__set`/`__call`, so `$el->href('x')`,
  `$el->href = 'x'`, `$el->setHref('x')` all set `href`) - **but `setText`, `setHtml`,
  `setName` are real methods, not attribute setters.** `Html::el(null)` is a nameless
  element that renders only its children; `Html::fragment(...)` creates one pre-filled
  and `$el->add(...)` appends with the same semantics (plain strings are **escaped**,
  unlike in `addHtml()`). `Html::text()`/`Html::html()`
  are static factories via `__callStatic` (not real methods); instance calls
  `$el->text()`/`$el->html()` are deprecated and warn. See `docs/internals.md`.
- **`Image` has two inverted color scales:** `ImageColor` opacity runs 0-1 while the
  legacy GD `alpha` runs 0-127. Requires `ext-gd`.
- **`FileSystem` is destructive by default:** `copy()`/`rename()` overwrite unless
  told otherwise, a directory copy **replaces** (wipes) an existing target, and
  `delete()` is recursive. Parent directories are auto-created.
- **`DateTime` extends mutable `\DateTime`**, not `DateTimeImmutable` - `modify()`
  changes the instance in place.
- **`SmartObject` still dispatches `$obj->onEvent(...)` via `__call`** over an
  `$onEvent` callback array - magic invisible to IDEs/PHPStan (`Arrays::invoke` is the
  explicit equivalent).
- **`Finder` is one of the two real engines.** `glob()` resolves the fixed prefix and
  manual traversal handles `**` (`splitRecursivePart`); `from()` = `in()` + `/**`;
  case-sensitivity is platform-dependent (the `i` flag is added only on Windows); and
  **`exclude()` uses a separate ad-hoc grammar** that diverges from the find-mask
  grammar - a documented source of silent mismatches. See `docs/internals.md`.
- **`Process` is the other engine** (stateful, wraps `proc_open`).
  `runExecutable()` bypasses the shell (argv array, no escaping); `runCommand()` goes
  through the shell - never pass unescaped input to it. **Default `$timeout` is 60 s,
  not unlimited.** All result getters (`getExitCode`, `getStdOutput`, ...) block until
  the process finishes; `consumeStdOutput()` is the incremental API. **`__destruct`
  kills a still-running process.** Piping one `Process` into another is unsupported
  on Windows. See `docs/internals.md`.
- User-facing how-to (the per-class API catalog, `Validators` expected-types syntax,
  Finder usage, v4.0 migration) is manual material and lives in the public web docs.
