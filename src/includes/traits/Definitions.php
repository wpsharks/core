<?php
namespace WebSharks\Core\Traits;

/**
 * String Definitions.
 *
 * @since 150424 Initial release.
 */
trait Definitions
{
    /**
     * Ampersand entities; keys are regex patterns.
     *
     * @type array Ampersand entities; keys are regex patterns.
     */
    protected $def_ampersand_entities = array(
        '&amp;'       => '&amp;',
        '&#0*38;'     => '&#38;',
        '&#[xX]0*26;' => '&#x26;',
    );

    /**
     * HTML whitespace; keys are regex patterns.
     *
     * @type array HTML whitespace; keys are regex patterns.
     */
    protected $def_html_whitespace = array(
        '\0'                         => "\0",
        '\x0B'                       => "\x0B",
        '\s'                         => "\r\n\t ",
        '\xC2\xA0'                   => "\xC2\xA0",
        '&nbsp;'                     => '&nbsp;',
        '\<br\>'                     => '<br>',
        '\<br\s*\/\>'                => '<br/>',
        '\<p\>(?:&nbsp;|\s)*\<\/p\>' => '<p></p>',
    );

    /**
     * Quote entities; keys are regex patterns.
     *
     * @type array Quote entities; keys are regex patterns.
     */
    protected $def_quote_entities = array(
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
    );

    /**
     * Multibyte detection order.
     *
     * @type array Default character encoding detections.
     */
    protected $def_mb_detection_order = array('UTF-8', 'ISO-8859-1');

    /**
     * Finds a double quoted value.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    protected $def_regex_frag_dq_value = '(?P<open_dq>(?<!\\\\)")(?P<dq_value>(?s:\\\\.|[^\\\\"])*?)(?P<close_dq>")';

    /**
     * Finds a single quoted value.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    protected $def_regex_frag_sq_value = '(?P<open_sq>(?<!\\\\)\')(?P<sq_value>(?s:\\\\.|[^\\\\\'])*?)(?P<close_sq>\')';

    /**
     * Finds a single or double quoted value.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    protected $def_regex_frag_dsq_value = '(?P<open_dsq>(?<!\\\\)["\'])(?P<dsq_value>(?s:\\\\.|(?!\\\\|(?P=open_dsq)).)*?)(?P<close_dsq>(?P=open_dsq))';
}
