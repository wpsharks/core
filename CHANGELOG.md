## $v

- Adding `c::varToCamelCase()`.
- Adding `c::activeSelected()`.
- Adding CSS filter shadow utilities.
- Enhancing inverted CSS selectors in SUI.
- Optimizing CSS utilities that set unit values.
- Bug fix. `curl_errno` should not impact the `http_code` return value.
- Updating to latest release of PHP Markdown fork.
- Enhancing monospace styles.

## v170329.13807

- Adding `c::stripeCustomer()`.
- Adding `c::stripeUpdateCustomer()`.
- Adding `c::stripeCharge()`.
- Adding `c::stripePlan()`.
- Adding `c::stripeSubscription()`.
- Adding `c::stripeJsTokenMarkup()`.
- Adding `c::escSq()`.
- Adding `c::escDq()`.
- Adding Semantic UI custom build directory `src/client-s/css/semantic`.
- Rewrote many CSS utility classes to improve optimization.
- Added many new CSS utility classes for common styles.
- Adding responsive reCAPTCHA style utilities.

## v170309.60830

- Adding Google reCAPTCHA verification utility; for reCAPTCHA v2.

- Adding package: [Defuse: `defuse/php-encryption`](https://github.com/defuse/php-encryption).

- Deprecating `Rijndael256{}` class, which uses `mcrypt` (abandonware), in favor of Defuse for cryptography. The `c::encrypt()` and `c::decrypt()` Facades now point to Defuse in order to avoid problems when running on modern versions of PHP, where `mcrypt` has been removed entirely starting with PHP v7.2.

  If you're running an older version of PHP and need `Rijndael256{}` encryption, please use `c::rjEncrypt()` and `c::rjDecrypt()` as a short-term transitional workaround.

- Adding a new configuration key: `©hash['©key']` as a general purpose hash key fallback, which is currently used by calls to `c::sha256KeyedHash()` whenever no specific key is given.

- Altering the intended use of configuration values: `©encryption['©key']` and `©cookies['©encryption_key']`. These should now be set to Defuse encryption keys in ASCII/hex format. Existing core-based applications that have these configuration values set to a 64-byte random key will need to update them.

  As a short-term backward compatibility feature, if your keys are not in a valid Defuse format, the older Rijndael256 encryption will be attempted automatically, instead of using the newer Defuse library, which absolutely requires that you establish Defuse encryption keys. Defuse encryption keys in hex format, always begin with `def00000`.

  Generating a Defuse encryption key:
  - `$key = c::encryptionKey()` and save the output.
  - Paste the `def00000...` key into your config file as: `©encryption['©key']`.
  - Repeat and create another key for `©cookies['©encryption_key']`.

- Adding support for OpenType font features in our SCSS framework.

- Adding `._content` variables to the SCSS framework for enhanced `._content` when desirable.

- Adding `._inset` utility to the SCSS framework.

- Making all SCSS variables the `!default` value.

- Fixed vertical alignment in fancy pagination links.

## v170218.30778

- Adding `@position-offscreen` mixin.
- Adding support for recursive SRI lookups.
- Enhancing heading ID generator in MD parser.

## v170215.53419

- Adding `c::memcacheInfo()`.
- Adding `c::memcacheEnabled()`.
- Adding `c::requestResponse()`.
- Adding SRI utils w/ `c::sri([url])`.
- Adding SmartyPants to Markdown parser.
- Removing `parsedown-extra` flavor in Markdown parser.
- Adding `c::backtraceCallers()` and `c::hasBacktraceCaller()`.
- Supporting new `hard_wrap` feature in PHP Markdown Extra flavor.
- The `smartypants` option in the Markdown parser is now enabled by default.
- Enhancing Anchorizer class that is used by the Markdown parser.
- Adding `/typings` directory for TypeScript projects.
- Adding custom compilation of Emmet + snippets.
- Adding custom Emmet extension for ACE.
- Deprecating `return_array` in `UrlRemote{}` class.
- Adding `max_stream_bytes` argument to `UrlRemote{}` class.

## v170124.74961

- Enhanced debugging in Slack notifier utility.
- Adding `c::escHtmlChars()` (escapes `<>&` only).
- Bug fix. Incorrect return type in `SubstrReplace{}` class.
- Enhanced string tokenizer by adding recursion to many regex patterns.
- The `PhpHas{}` class is now smart enough to detect allowable paths via `opcache.restrict_api`.
- Added `c::currentUrlPort()`, `c::currentRootPort()`, and enhanced several URL-related methods.
- Enhancing `c::clip()` and `c::midClip()`. Now accepts a custom `$more` argument.
- Corrected some minor bugs in WRegex parsing engine.
- Adding new Slack utility: `c::slackMrkdwn()`.
- Adding GitHub API and parsing utilities.
- Adding ZenHub API utilities.
- Enhancing CSS framework.
- Adding `c::humanTimeDiff()`.

## v161013.18318

- Enhancing `Error{}` class.
- Adding `.webp` extension support.
- Adding a default set of templates for general purpose web applications.
- Enhancing core SCSS and adding a new set of mixins.
- Enhancing router by adding support for endpoint query vars.
- Adding new Facade that makes it easier to encode/decode a single ID.
- Adding `Paginator{}` class and utilities.
- Adding `StHighlighter{}` and utilities.
- Adding `Route{}` and utilities.
- Adding `c::contrastingFgColor()`.
- Adding `c::slackNotify()` utility.
- Adding `c::compressPng()` utility.
- Adding `c::decodeImageDataUrl()` utility.
- Adding several color utilities.

## v160831.61689

- Additional `c::arrayToXml()` bug fix. Allow for duplicate tags.

## v160831.49522

- `c::arrayToXml()` bug fix. Allow for duplicate tags.

## v160829.74007

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
