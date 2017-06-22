<?php
/**
 * HTML-related constants.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * HTML-related constants.
 *
 * @since 150424 Initial release.
 */
interface HtmlConstants
{
    /**
     * Ampersand entities; keys are regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array Ampersand entities.
     */
    const HTML_AMPERSAND_ENTITIES = [
        '&amp;'       => '&amp;',
        '&#0*38;'     => '&#38;',
        '&#[xX]0*26;' => '&#x26;',
    ];

    /**
     * Whitespace regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array HTML whitespace.
     */
    const HTML_WHITESPACE = [
        '\0',
        '\x0B',
        '\s',
        '\xC2\xA0',
        '&nbsp;',
        '\<[Bb][Rr]\s*(?:\/\s*)?\>',
        '\<[Pp]\>(?:&nbsp;|\s)*\<\/[Pp]\>',
    ];

    /**
     * Horizontal whitespace regex patterns.
     *
     * @since 160720 Initial release.
     *
     * @type array Vertical HTML whitespace.
     */
    const HTML_HORIZONTAL_WHITESPACE = [
        '\0',
        '\x0B',
        "\t",
        ' ',
        '\xC2\xA0',
        '&nbsp;',
    ];

    /**
     * Vertical whitespace regex patterns.
     *
     * @since 160720 Initial release.
     *
     * @type array Vertical HTML whitespace.
     */
    const HTML_VERTICAL_WHITESPACE = [
        "\r",
        "\n",
        '\<[Bb][Rr]\s*(?:\/\s*)?\>',
        '\<[Pp]\>(?:&nbsp;|\s)*\<\/[Pp]\>',
    ];

    /**
     * Quote entities; keys are regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array Quote entities.
     */
    const HTML_QUOTE_ENTITIES = [
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
     * HTML5 invisible tag patterns.
     *
     * @since 150424 Initial release.
     * @since 17xxxx Converting to patterns.
     *
     * @type array HTML5 invisible tag patterns.
     */
    const HTML_INVISIBLE_TAGS = [
        'head',
        'link',
        'meta',
        'noscript',
        'script',
        'style',
        'template',
        'title',
    ];

    /**
     * HTML5 block-level tag patterns.
     *
     * @since 150424 Initial release.
     * @since 17xxxx Converting to patterns.
     *
     * @type array HTML5 block-level tag patterns.
     */
    const HTML_BLOCK_TAGS = [
        '[a-z][a-z\-0-9]*\-div',
        'address',
        'article',
        'aside',
        'audio',
        'blockquote',
        'canvas',
        'dd',
        'div',
        'dl',
        'dt',
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
        'iframe',
        'li',
        'main',
        'nav',
        'noscript',
        'ol',
        'output',
        'p',
        'pre',
        'section',
        'table',
        'tfoot',
        'thead',
        'ul',
        'video',
    ];

    /**
     * HTML5 block-level tag patterns.
     *
     * @since 17xxxx Converting to patterns.
     *
     * @type array HTML5 block-level tag patterns.
     *
     * @note Blocks when on a line of their own.
     */
    const HTML_MAYBE_BLOCK_TAGS = [
        '[a-z][a-z\-0-9]*\-(?!div)[a-z0-9]+',
    ];
}
