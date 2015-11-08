<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

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
     * @type array Ampersand entities; keys are regex patterns.
     */
    const HTML_AMPERSAND_ENTITIES = [
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
    const HTML_WHITESPACE = [
        '\0'                               => "\0",
        '\x0B'                             => "\x0B",
        '\s'                               => "\r\n\t ",
        '\xC2\xA0'                         => "\xC2\xA0",
        '&nbsp;'                           => '&nbsp;',
        '\<[Bb][Rr]\s*(?:\/\s*)?\>'        => '<br/>',
        '\<[Pp]\>(?:&nbsp;|\s)*\<\/[Pp]\>' => '<p></p>',
    ];

    /**
     * Quote entities; keys are regex patterns.
     *
     * @since 150424 Initial release.
     *
     * @type array Quote entities; keys are regex patterns.
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
     * HTML5 invisible tags.
     *
     * @since 150424 Initial release.
     *
     * @type array HTML5 invisible tags.
     */
    const HTML_INVISIBLE_TAGS = [
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
    const HTML_BLOCK_TAGS = [
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
}
