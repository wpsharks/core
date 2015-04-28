<?php
namespace WebSharks\Core\Interfaces;

/**
 * Constants.
 *
 * @since 150424 Initial release.
 * @see http://jas.xyz/1FvMslK
 */
interface Constants
{
    # Multipurpose.

    /**
     * @type string Represents `core`.
     */
    const CORE = '___core___';

    /**
     * @type string Represents `all`.
     */
    const ALL = '___all___';

    /**
     * @type string Represents `any`.
     */
    const ANY = '___any___';

    /**
     * @type string Represents `own`.
     */
    const OWN = '___own___';

    /**
     * @type string Represents `defaults`.
     */
    const DEFAULTS = '___defaults___';

    /**
     * @type string Represents `direct`.
     */
    const DIRECT = '___direct___';

    /**
     * @type string Represents `prepend`.
     */
    const PREPEND = '___prepend___';

    /**
     * @type string Represents `append`.
     */
    const APPEND = '___append___';

    /**
     * @type string Represents `replace`.
     */
    const REPLACE = '___replace___';

    /**
     * @type string Reconsider.
     */
    const RECONSIDER = true;

    /**
     * @type bool Yes, do echo.
     */
    const DO_ECHO = true;

    /**
     * @type bool No echo.
     */
    const NO_ECHO = false;

    # Data types.

    /**
     * @type string Represents `boolean`.
     */
    const BOOLEAN_TYPE = '___boolean_type___';

    /**
     * @type string Represents `float`.
     */
    const FLOAT_TYPE = '___float_type___';

    /**
     * @type string Represents `string`.
     */
    const STRING_TYPE = '___string_type___';

    /**
     * @type string Represents `integer`.
     */
    const INTEGER_TYPE = '___integer_type___';

    /**
     * @type string Represents `object`.
     */
    const OBJECT_TYPE = '___object_type___';

    /**
     * @type string Represents associative `array`.
     */
    const ARRAY_A_TYPE = '___array_a_type___';

    /**
     * @type string Represents numeric `array`.
     */
    const ARRAY_N_TYPE = '___array_n_type___';

    # RFC types (standards).

    /**
     * @type string Represents rfc1738.
     */
    const RFC1738 = '___rfc1738___';

    /**
     * @type string Represents rfc3986.
     */
    const RFC3986 = '___rfc3986___';

    # Regex flavors.

    /**
     * @type string Represents PHP regex.
     */
    const REGEX_PHP = '___regex_php___';

    /**
     * @type string Represents JavaScript regex.
     */
    const REGEX_JS = '___regex_js___';

    # String replacement types.

    /**
     * @type string Represents `preg_replace()`.
     */
    const PREG_REPLACE = '___preg_replace___';

    /**
     * @type string Represents `str_replace()`.
     */
    const STR_REPLACE = '___str_replace___';

    # Permission types.

    /**
     * @type string Represents a `public` type.
     */
    const PUBLIC_TYPE = '___public_type___';

    /**
     * @type string Represents a `protected` type.
     */
    const PROTECTED_TYPE = '___protected_type___';

    /**
     * @type string Represents a `private` type.
     */
    const PRIVATE_TYPE = '___private_type___';

    # MIME types.

    /**
     * @type string Represents `textual`.
     */
    const TEXTUAL = '___textual___';

    /**
     * @type string Represents `compressable`.
     */
    const COMPRESSABLE = '___compressable___';

    /**
     * @type string Represents `cacheable`.
     */
    const CACHEABLE = '___cacheable___';

    /**
     * @type string Represents `binary`.
     */
    const BINARY = '___binary___';

    # Filesystem types.

    /**
     * @type string Represents `file`.
     */
    const FILE = '___file___';

    /**
     * @type string Represents `dir`.
     */
    const DIR = '___dir___';

    # Exclusion types.

    /**
     * @type string Represents `ignore_globs` array key.
     */
    const IGNORE_GLOBS = '___ignore_globs___';

    /**
     * @type string Represents `ignore_extra_globs` array key.
     */
    const IGNORE_EXTRA_GLOBS = '___ignore_extra_globs___';

    /**
     * @type string Represents `gitignore` array key.
     */
    const GITIGNORE = '___gitignore___';

    # URL parts/components bitmask.

    /**
     * @type int Indicates scheme component in a URL.
     */
    const URL_SCHEME = 1;

    /**
     * @type int Indicates user component in a URL.
     */
    const URL_USER = 2;

    /**
     * @type int Indicates pass component in a URL.
     */
    const URL_PASS = 4;

    /**
     * @type int Indicates host component in a URL.
     */
    const URL_HOST = 8;

    /**
     * @type int Indicates port component in a URL.
     */
    const URL_PORT = 16;

    /**
     * @type int Indicates path component in a URL.
     */
    const URL_PATH = 32;

    /**
     * @type int Indicates query component in a URL.
     */
    const URL_QUERY = 64;

    /**
     * @type int Indicates fragment component in a URL.
     */
    const URL_FRAGMENT = 128;

    # Glob bitmask w/ additional options.

    /**
     * @type int Adds a slash to each directory returned.
     */
    const GLOB_MARK = 1;

    /**
     * @type int Returns files as they appear in the directory (no sorting).
     */
    const GLOB_NOSORT = 2;

    /**
     * @type int Return the search pattern if no files matching it were found.
     */
    const GLOB_NOCHECK = 4;

    /**
     * @type int Backslashes do not quote metacharacters.
     */
    const GLOB_NOESCAPE = 8;

    /**
     * @type int Expands `{a,b,c}` to match `'a'`, `'b'`, or `'c'`.
     */
    const GLOB_BRACE = 16;

    /**
     * @type int Return only directory entries which match the pattern.
     */
    const GLOB_ONLYDIR = 32;

    /**
     * @type int Stop on read errors.
     */
    const GLOB_ERR = 64;

    /**
     * @type int Use `[aA][bB]` to test caSe variations.
     */
    const GLOB_CASEFOLD = 128;

    /**
     * @type int Finds hidden dot `.` files w/ wildcards.
     */
    const GLOB_PERIOD = 256;
}
