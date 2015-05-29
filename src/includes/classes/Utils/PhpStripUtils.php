<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * PHP strip utilities.
 *
 * @since 150424 Initial release.
 */
class PhpStripUtils extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Strips PHP tags.
     *
     * @since 150424 Initial release.
     *
     * @param string $string Input string to strip.
     *
     * @return string String w/ all PHP tags stripped away.
     */
    public function phpTagsStrip($string)
    {
        $string = (string) $string;

        return preg_replace(
            '/'.// Open pattern delimiter.

            '(?:'.// Any of these.

            '\<\?php.*?\?\>'.
            '|'.
            '\<\?\=.*?\?\>'.
            '|'.
            '\<\?.*?\?\>'.
            '|'.
            '\<%.*?%\>'.
            '|'.
            '\<script\s+[^>]*?language\s*\=\s*(["\'])php\\1[^>]*\>.*?\<\s*\/\s*script\s*\>'.
            '|'.
            '\<script\s+[^>]*?language\s*\=\s*php[^>]*\>.*?\<\s*\/\s*script\s*\>'.

            ')'.// Close 'Any of these'.

            '/is', // End pattern.
            '', // Replace with empty string.
            $string
        );
    }
}
