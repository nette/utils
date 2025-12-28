# Utils internals

`nette/utils` is mostly self-explanatory static helpers — their behavior is clear
from signatures and does not belong here. This file is the thin "conventions &
traps" layer: the few facts that are expensive to rediscover, plus two genuine
engines (`Finder` and `Process`) that warrant their own sections.

## Conventions & traps (per subsystem)

### Arrays vs Iterables: eager array vs lazy Generator

Two parallel APIs with the **same method names** but a hard dividing line that is
itself the invariant:

- `Arrays::*` operate on native arrays, are **eager**, and return arrays.
- `Iterables::*` operate on any `iterable`, are **lazy**, and often return a
  **`Generator`**.

The trap: `Arrays::map` gives you a re-usable array; `Iterables::map` gives you a
**single-use, non-rewindable** Generator — iterate it twice and the second pass is
empty. Some `Arrays` methods still accept `iterable` inputs, which blurs the line
(a standing cleanup, `arrays-deprecace-iterable`). When an agent needs a value it
can traverse more than once, it must not hand back an `Iterables` result.

### Strings: UTF-8 assumed, extensions required

`Strings` assumes **valid UTF-8** input and leans on PHP extensions, but crucially
**not uniformly** — some methods hard-fail without their extension, others degrade:

- **Hard requirements (no guard, fatal if missing):** `lower`/`upper` (and
  therefore `compare`, which lower-cases) need `mbstring`; `toAscii` (and thus
  `webalize`, which calls it) throws `NotSupportedException` without `intl`;
  `chr`/`ord`/`reverse` throw without `iconv`.
- **Graceful degradation:** `normalize`/`compare` skip NFC when `intl`'s
  `Normalizer` is absent; `length`/`substring` fall back `mbstring`→`iconv`.
- Inside `toAscii`, `iconv` is used only **opportunistically** (per `ICONV_IMPL`),
  never as the primary transliteration path — a common misconception.

There is also a latent `mb_*` vs `preg_*` (PCRE `u` mode) inconsistency to watch.
These are the "works on my machine, breaks in that container" facts.

**Regex failures are escalated, never signalled by return value.** Every regex
API (`match`, `matchAll`, `replace`, `split`) funnels through `Strings::pcre()`,
which converts both compile-time and run-time PCRE failures into
`RegexpException` — deliberately, because `preg_last_error()` and the `false`
return are unreliable ("liars"). Consequence of the `u` modifier: an invalid
UTF-8 *subject* throws `RegexpException` at runtime; this is how the "assumes
valid UTF-8" contract actually manifests.

### Json: silently different defaults than native json_*

- `decode()` always adds **`JSON_BIGINT_AS_STRING`** — an integer exceeding
  `PHP_INT_MAX` quietly decodes as a *string*, not a float. The classic
  "works here, breaks there" fact of this class.
- `decode()` returns `stdClass` by default (`forceArrays: true` for arrays);
  `encode()` defaults to `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES`
  (unlike bare `json_encode`), plus `JSON_PRESERVE_ZERO_FRACTION`.
- Both throw `JsonException` instead of returning `false`/`null`.

### Html: attribute magic that collides with real methods

`Html` sets attributes through **magic** (`__set`, `__call`) with `set`/`get`/`add`
prefixes and a prefix-less form, so `$el->href('x')`, `$el->href = 'x'`, and
`$el->setHref('x')` all set the `href` attribute. The trap: a few names are
**real methods, not attribute setters** — `setText`, `setHtml`, `setName`, etc. —
so `$el->setTitle(...)` sets an attribute but `$el->setText(...)` calls the method.
`Html::el(null)` builds a **nameless element** that renders only its children (no
surrounding tag).

### Image: two color representations on opposite scales

A color is either an `ImageColor` object or a legacy GD array, and the two use
**inverted transparency scales** — the classic trap here: the GD array's `alpha`
runs 0–127 while `ImageColor`'s `opacity` runs 0–1 (opposite direction). `Image`
requires `ext-gd`.

### FileSystem: destructive by default

The defaults are the opposite of cautious POSIX habits:

- `copy()` and `rename()` **overwrite by default** (`$overwrite = true`); pass
  `false` to get the guard.
- Copying a directory over an existing one **first deletes the target's current
  contents**, then copies — it is a replace, not a merge.
- `delete()` is **recursive** on directories and silently succeeds when the path
  does not exist.
- `write()`, `copy()` and `rename()` auto-create missing parent directories.

All failures throw `IOException` (via `@` escalated to exception), never return
`false`.

### DateTime: mutable, against modern expectations

`Nette\Utils\DateTime` extends **mutable `\DateTime`**, not `DateTimeImmutable`
— `modify()` and setters change the instance in place. Do not pass it around
assuming value semantics. `from()` accepts a timestamp (int), a string, or any
`DateTimeInterface`.

### SmartObject / event magic

`SmartObject` provides strict property access; it **still** dispatches
`$obj->onEvent(...)` by iterating an `$onEvent` array of callbacks via `__call`
(when the property is recognized as an `event`) — magic invisible to IDEs and
static analysis. `Arrays::invoke($obj->onEvent, ...)` is the explicit, analyzable
equivalent that has not replaced it.

### CachingIterator does not cache generators

Counterintuitively, `Iterators\CachingIterator` does **not** make a `Generator`
re-iterable (it is constructed with SPL flags that omit full caching). To iterate
an arbitrary `iterable` more than once, use `Iterables::memoize()`, which wraps it
so repeated traversal replays recorded items.

### Type: immutable value object over the PHP type system

`Type` is an **immutable** value object modelling PHP's union / intersection / DNF
types; it replaced the removed `Reflection::get*Type()` helpers and is the pattern
to follow for similar value objects. Nothing mutates it after construction.

## Finder: the one real engine

`Finder` is the only non-thin subsystem. Its search is an **emergent two-stage
model** you only see by tracing `buildPlan` → `splitRecursivePart` →
`buildPattern` → `traverseDir`:

- **glob does the fixed prefix, manual traversal does the `**`.** Because native
  `glob()` has no `**` wildcard, `splitRecursivePart` cuts each mask at the first
  `**` segment: the part before becomes a `glob()` base (which resolves `*`, `?`,
  `[...]`), the part after becomes a regex applied while walking directories
  (`traverseDir`). This split is the core of the engine.
- **`from()` = `in()` + `/**`.** `in()` searches one level; `from()` appends `/**`
  to the location so traversal recurses. The two entry styles differ only by that
  suffix.
- **Mask grammar → regex** (`buildPattern`): `**/`→`(.+/)?`, `*`→`[^/]*` (never
  crosses `/`), `?`→`[^/]`, `[...]`/`[!...]` character classes. Anchoring is the
  subtle bit: a `./` prefix anchors to the **search root** (`^`), otherwise the
  pattern may match at **any segment boundary** (`(?:^|/)`). **Case sensitivity is
  platform-dependent** — the `i` flag is added only on Windows (`Helpers::IsWindows`),
  so identical code is case-sensitive on Linux and insensitive on Windows.
- **`exclude()` is a separate, ad-hoc parser** — not the same grammar as the find
  masks. It matches its own regex (`~^/?(\*\*/)?(.+)(/\*\*|/\*|/|)$~D`) and the
  **trailing marker** (`/**`, `/*`, `/`, or none) decides whether the exclusion is
  applied as a descent filter, a result filter, or both. This divergence is a
  documented source of silent mismatches between what `findFiles` and `exclude`
  masks mean; treat the two grammars as distinct.
- **Filter results are memoized per file.** `proveFilters` caches each filter's
  outcome by `spl_object_id` within a single file's evaluation, so a closure used
  as both a `descentFilter` and a `filter` (as `exclude` does) is not evaluated
  twice.
- **Descent vs result filters are different gates.** `descentFilters` decide
  whether traversal enters a subdirectory; `filters` decide whether an entry is
  yielded. `childFirst` flips pre-order to post-order. On Windows, symlink entries
  are classified by their **target**.

## Process: the second engine

`Process` is a stateful wrapper over `proc_open` (the only other non-thin
subsystem). Its traps are behavioral, not structural:

- **Two entry points differ by shell involvement.** `runExecutable()` takes an
  argv array — the shell is never involved, no escaping, no injection.
  `runCommand()` hands the string to `/bin/sh` / `cmd.exe` — never pass unescaped
  user input to it.
- **The default `$timeout` is 60 s, not unlimited.** It is enforced only while
  waiting for or reading the process (`wait`, `consume*`); on expiry the process
  is terminated and `ProcessTimeoutException` thrown.
- **All result getters block.** `wait()`, `getExitCode()`, `isSuccess()`,
  `ensureSuccess()`, `getStdOutput()`/`getStdError()` wait for completion. The
  incremental API is `consumeStdOutput()`/`consumeStdError()`: poll them
  `while ($p->isRunning())`, then call **once more after the loop** for the tail
  produced just before exit.
- **Output capture is decided at construction.** `null` target → captured into a
  memory buffer; string → file; caller resource → redirect (must be backed by a
  real OS file descriptor, not `php://memory`); `false` → discard. The `get*` /
  `consume*` readers throw `InvalidStateException` when that stream was not
  captured.
- **`__destruct` kills a still-running process** — dropping the last reference
  terminates the child. `terminate()` is untrappable by design: SIGKILL on POSIX
  (so the following `proc_close()` cannot hang), `taskkill /F /T` on Windows.
- **Piping transfers pipe ownership and is POSIX-only.** Passing a `Process` as
  `$stdin` hands over the source's STDOUT pipe — the source can no longer read
  its own output — and throws `NotSupportedException` on Windows.
- **String/resource `$stdin` is written upfront, synchronously.** A large input
  that the child does not read while filling its own output can block; pass
  `null` and feed via `writeStdInput()` instead.
- **Windows capture differs by PHP version.** On Windows < PHP 8.5 captured
  output is backed by a `tmpfile()` (`stream_select()` did not work on
  `proc_open` pipes before the 8.5 PeekNamedPipe fix); elsewhere it is anonymous
  pipes drained non-blockingly via `stream_select()` (`readFromPipe`).

## Navigation map

| Concern | Where |
|---|---|
| Eager/lazy divide | `Arrays` vs `Iterables` |
| UTF-8 + extension needs | `Strings` |
| Regex failure escalation | `Strings::pcre` |
| Big-int decode, encode defaults | `Json` |
| Destructive-by-default file ops | `FileSystem::copy`, `rename`, `delete` |
| Mutable date-time | `DateTime` |
| Attribute magic vs real methods | `Html::__call`, `__set` |
| Color scale trap | `Image`, `ImageColor` |
| Re-iteration | `Iterables::memoize` (not `CachingIterator`) |
| Search plan, `**` split | `Finder::buildPlan`, `splitRecursivePart`, `buildPattern` |
| Exclude grammar divergence | `Finder::exclude` |
| Traversal, descent vs result filters | `Finder::traverseDir`, `proveFilters` |
| Shell vs no-shell process start | `Process::runCommand` vs `runExecutable` |
| Blocking vs incremental output reads | `Process::getStdOutput` vs `consumeStdOutput` |
| Capture targets, platform divergence | `Process::createOutputDescriptor`, `readFromPipe` |
