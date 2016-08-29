## $v

- Adding `c::arrayToXml()`.
- Adding `c::arrayToHtml()`.

## v160827.7391

- Bug fix. `isSlug()` was requiring a minimum of three chars; based a prior implementation. Updated so that only `isSlugReserved()` makes this check.

## v160726.73683

- `$compress` parameter to `c::normalizeEols()` now defaults to `false`.
- Enhancing docBlocks throughout for improved codex generation coming soon.

## v160721.58607

- Adding `c::stripLeadingIndents()`.
- Refactoring `Classes\Tokenizer{}`.
- Making it possible to tokenize specific shortcode tag names in `Classes\Core\Tokenizer{}`.
- Adding `$compress` parameter to `c::normalizeEols()` to allow for compression to be disabled there.
- Adding `c::removeKey()` and `c::removeKeys()`.

## v160719.52827

- Bug fix. `AwsLib` instead of `Aws`, because this name conflicts with the utility that calls it.

## v160719.39064

- Bumping AWS library integration to v3.
- Adding `c::awsSdk()` and `c::awsS3Client()`.
- Dropping `PDO` extension requirement (now optional).
- Dropping `posix` extension requirement (now optional).

## v160716.56490

- Adding `c::appParent()`.
- Adding `c::appCore()`.

## v160716.38505

- Adding `setAdditional()` props to `Classes\Core\Template{}` for extenders.

## v160715.30802

- Bug fix in Simple Expression syntax. Correctly detect `===` operator.
- Bug fix in Simple Expression syntax. Allow a jump from `()` to a new test-fragment that is not in brackets; e.g., `(a) OR b`.

## v160714.32466

- Bug fix. `$_SERVER` instead of `$_`.

## v160714.26155

- Updating build & composer dependencies.

## v160713.19938

- Enhancing `Classes\Utils\Serializer{}`.

## v160713.3972

- Adding serializable Closures.
- Adding `c::serializeClosure()`.
- Adding `c::unserializeClosure()`.

## v160712.82762

- Enhancing `c::canCallFunc()`.

## v160712.38316

- Optimizing base App class for speedier instantiation.
- Deprecating `$args` param to App constructor.
- Adding multibyte support in `Classes\App::getClass()`.
- Deprecating `c::config()` in favor of `Classes\App::Config()`.
- Deprecating `c::version()` in favor of `Classes\App::VERSION`.
- Deprecating `c::diGet()` in favor of `Classes\App::$Di::get()`.
- Exposing `->f` property in base abstraction as public/read-only.
- Refactor `Classes\Core\Utils\Exceptions{}`; now `Classes\Core\Utils\Throwables{}`.
- Exposing `->c`, `->s`, and `->a` properties in base abstraction as public/read-only.
- Refactor: `Classes\App::$facaces` is now `Classes\App::$Facades` (`\StdClass` instead of an array).
- Removing support for an external `config.json` file in favor of `$instance_base` and `$instance`.

## v160711.18232

- Refactor `Classes\Core\Error{}`.

## v160711.3260

- Adding `Classes\Core\Error{}`.
- Adding `c::error()`.
- Adding `c::isError()`.

## v160710.79300

- Distinguish WSC from another secondary core with respect to `->is_core` and `->core_dir_*` vars.

## v160709.27664

- Bug fix in `c::sQuote()`
- Bug fix in `c::dQuote()`

## v160709.16106

- Adding `c::sQuote()`
- Adding `c::dQuote()`
- Bug fix in `c::escShellArg()` related to iteration of array/object values.
- Bug fix in `c::escShellArg()` related to an empty string.

## v160709.2110

- Adding `Interfaces\SimpleExpressionConstants{}`.
- Adding `Interfaces\SimpleExpressionConstants\SIMPLE_EXPRESSION_REGEX_FRAG`.
- Adding `Interfaces\SimpleExpressionConstants\SIMPLE_EXPRESSION_REGEX_VALID`.
- Adding `Interfaces\SimpleExpressionConstants\SIMPLE_EXPRESSION_BOOL_ONLY_REGEX_FRAG`.
- Adding `Interfaces\SimpleExpressionConstants\SIMPLE_EXPRESSION_BOOL_ONLY_REGEX_VALID`.
- Adding `Classes\Core\Utils\SimpleExpression::toPhp()`.
- Adding `c::simplePhpExpr()`.
- Adding local tests in `simple-expr.php` w/ benchmarks.

## v160703.71181

- Introducing new config key: `©fs_paths.©templates_dir`.
- Introducing new config key: `©encryption.©key` as a generic fallback.
- Improving template locator. Now handling parent/core templates in a more dynamic way.

## v160702.65000

- Adding `UniqueId` class and `uniqueId()` Facade.
- Adding local `StrPad{}` test file.

## v160701.57492

- Use `$GLOBALS['wp_version']` instead of `WP_VERSION` const.
- MIME type updates. `application/x-javascript` now `application/javascript`. Also adding `x-php` extension.
- Security hardending in `/tmp` directory detection.

## v160630.68424

- Refactor hash IDs.

## v160629.59718

- Updating to latest websharks/phings.
- Branch rename; `000000-dev` is now just `dev`.
- Exposing `f` and `facades` property as public; read-only.

## v160625.59856

- Enhancing base paths in URL generation.

## v160624.33052

- Adding `short_slug`, `short_var`, and removing `prefix`.

## v160621.41871

- Enhancing MailChimp utilities.

## v160621.35596

- Adding MailChimp utilities.

## v160620.27266

- Updating to Sharkicons v160620.

## v160608.37022

- Refactoring. `doingAction()` now `doingRestAction()` to avoid confusion w/ hooks.

## v160606.65620

- Adding `NoCache{}` class and enhancing `RequestType{}`

## v160606.45353

- Adding Facade `c::isApi()`.

## v160604.78151

- Updating dotfiles and enhancing build props.

## v160601.61090

- Bug fix in URL arg removal.

## v160601.57796

- Updating property names representing class instances.

## v160601.56004

- Enhancing template locater; adding `core` directive.
- Adding support for request-type detection; e.g., `isAjax()`, `doingAction()`, etc.
- Adding support for bleeding-edge debug mode.

## v160528.388

- Updating phing build system.
- Enhancing debug log callback system.

## v160524

- First public release.

## v160223

- Adding Facades for pseudo-static access.
- Removing `c\*` global functions.
- This release is NOT backward compatible.

## v15xxxx

- Initial release.
