<?php
namespace WebSharks\Core\Traits;

/**
 * Definition properties.
 *
 * @since 150424 Initial release.
 */
trait DefinitionProps
{
    /**
     * Ampersand entities; keys are regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array Ampersand entities; keys are regex patterns.
     */
    protected $def_ampersand_entities = [
        '&amp;'       => '&amp;',
        '&#0*38;'     => '&#38;',
        '&#[xX]0*26;' => '&#x26;',
    ];

    /**
     * HTML whitespace; keys are regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array HTML whitespace; keys are regex patterns.
     */
    protected $def_html_whitespace = [
        '\0'                         => "\0",
        '\x0B'                       => "\x0B",
        '\s'                         => "\r\n\t ",
        '\xC2\xA0'                   => "\xC2\xA0",
        '&nbsp;'                     => '&nbsp;',
        '\<br\>'                     => '<br>',
        '\<br\s*\/\>'                => '<br/>',
        '\<p\>(?:&nbsp;|\s)*\<\/p\>' => '<p></p>',
    ];

    /**
     * Quote entities; keys are regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array Quote entities; keys are regex patterns.
     */
    protected $def_quote_entities = [
        '&apos;'           => '&apos;',
        '&#0*39;'          => '&#39;',
        '&#[xX]0*27;'      => '&#x27;',
        '&lsquo;'          => '&lsquo;',
        '&#0*8216;'        => '&#8216;',
        '&#[xX]0*2018;'    => '&#x2018;',
        '&rsquo;'          => '&rsquo;',
        '&#0*8217;'        => '&#8217;',
        '&#[xX]0*2019;'    => '&#x2019;',
        '&quot;'           => '&quot;',
        '&#0*34;'          => '&#34;',
        '&#[xX]0*22;'      => '&#x22;',
        '&ldquo;'          => '&ldquo;',
        '&#0*8220;'        => '&#8220;',
        '&#[xX]0*201[cC];' => '&#x201C;',
        '&rdquo;'          => '&rdquo;',
        '&#0*8221;'        => '&#8221;',
        '&#[xX]0*201[dD];' => '&#x201D;',
    ];

    /**
     * HTML5 invisible tags.
     *
     * @since 150424 Initial release.
     *
     * @type array HTML5 invisible tags.
     */
    protected $def_html_invisible_tags = [
        'head',
        'title',
        'style',
        'script',
    ];

    /**
     * HTML5 block-level tags.
     *
     * @since 150424 Initial release.
     *
     * @type array HTML5 block-level tags.
     */
    protected $def_html_block_tags = [
        'address',
        'article',
        'aside',
        'audio',
        'blockquote',
        'canvas',
        'dd',
        'div',
        'dl',
        'fieldset',
        'figcaption',
        'figure',
        'footer',
        'form',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'header',
        'hgroup',
        'hr',
        'noscript',
        'ol',
        'output',
        'p',
        'pre',
        'section',
        'table',
        'tfoot',
        'ul',
        'video',
    ];

    /**
     * Multibyte detection order.
     *
     * @since 150424 Initial release.
     *
     * @type array Default character encoding detections.
     */
    protected $def_mb_detection_order = ['UTF-8', 'ISO-8859-1'];

    /**
     * Finds a double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    protected $def_regex_frag_dq_value = '(?P<open_dq>(?<!\\\\)")(?P<dq_value>(?s:\\\\.|[^\\\\"])*?)(?P<close_dq>")';

    /**
     * Finds a single quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    protected $def_regex_frag_sq_value = '(?P<open_sq>(?<!\\\\)\')(?P<sq_value>(?s:\\\\.|[^\\\\\'])*?)(?P<close_sq>\')';

    /**
     * Finds a single or double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    protected $def_regex_frag_dsq_value = '(?P<open_dsq>(?<!\\\\)["\'])(?P<dsq_value>(?s:\\\\.|(?!\\\\|(?P=open_dsq)).)*?)(?P<close_dsq>(?P=open_dsq))';

    /**
     * Regex matches a `scheme://`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `scheme://`.
     */
    protected $def_regex_frag_scheme = '(?:[a-zA-Z0-9]+\:)?\/\/';

    /**
     * Regex matches a `host` name.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `host` name (TLD optional).
     */
    protected $def_regex_frag_host = '[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?';

    /**
     * Regex matches a `host:port`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `host:port` (`:port` and TLD are optional).
     */
    protected $def_regex_frag_host_port = '[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?(?:\:[0-9]+)?';

    /**
     * Regex matches a `user:pass@host:port`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a `user:pass@host:port` (`user:pass@`, `:port`, and TLD are optional).
     */
    protected $def_regex_frag_user_host_port = '(?:[a-zA-Z0-9\-_.~+%]+(?:\:[a-zA-Z0-9\-_.~+%]+)?@)?[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?(?:\:[0-9]+)?';

    /**
     * Regex matches a valid URL.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex matches a valid `scheme://user:pass@host:port/path/?query#fragment` URL.
     *             Note: `scheme:`, `user:pass@`, `:port`, `TLD`, `path`, `query` and `fragment` are optional.
     */
    protected $def_regex_valid_url = '/^(?:[a-zA-Z0-9]+\:)?\/\/(?:[a-zA-Z0-9\-_.~+%]+(?:\:[a-zA-Z0-9\-_.~+%]+)?@)?[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*(?:\.[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*(?:\.[a-zA-Z][a-zA-Z0-9]+)?(?:\:[0-9]+)?(?:\/(?!\/)[a-zA-Z0-9\-_.~+%]*)*(?:\?(?:[a-zA-Z0-9\-_.~+%]+(?:\=[a-zA-Z0-9\-_.~+%&]*)?)*)?(?:#[^\s]*)?$/';

    /**
     * Plugin version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string Plugin version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP version strings).
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     */
    protected $def_regex_valid_ws_version = '/^(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)(?:\-(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?$/';

    /**
     * Plugin dev version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string Plugin dev version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP dev version strings).
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     *       4. Must have a development-state suffix; perhaps followed by an optional build suffix.
     */
    protected $def_regex_valid_ws_dev_version = '/^(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)(?:\-(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?$/';

    /**
     * Plugin stable version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string Plugin stable version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP stable version strings).
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     *       4. Must not contain a development-state suffix (i.e., it must be a stable version).
     *             However, it may contain an optional build suffix; and still be stable.
     */
    protected $def_regex_valid_ws_stable_version = '/^(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?$/';

    /**
     * PHP version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string PHP version string validation pattern.
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *          However, a development-state suffix does not have to start with a `-` if the first character is a letter; e.g., 1.1RC is fine.
     */
    protected $def_regex_valid_version = '/^(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9])(?:\-?(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?(?:\+(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?$/';

    /**
     * PHP dev version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string PHP dev version string validation pattern.
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *          However, a development-state suffix does not have to start with a `-` if the first character is a letter; e.g., 1.1RC is fine.
     *       2. Must have a development-state suffix; perhaps followed by an optional build suffix.
     */
    protected $def_regex_valid_dev_version = '/^(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9])(?:\-?(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))(?:\+(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?$/';

    /**
     * PHP stable version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string PHP stable version string validation pattern.
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *          However, a development-state suffix does not have to start with a `-` if the first character is a letter; e.g., 1.1RC is fine.
     *       2. Must not contain a development-state suffix (i.e., it must be a stable version).
     *             However, it may contain an optional build suffix; and still be stable.
     */
    protected $def_regex_valid_stable_version = '/^(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9])(?:\+(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?$/';
}
