<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * PHP strip utilities.
 *
 * @since 150424 Initial release.
 */
class PhpStrip extends Classes\AbsBase
{
    /**
     * Strips PHP tags.
     *
     * @since 150424 Initial release.
     *
     * @param string $string Input string to strip.
     *
     * @return string String w/ all PHP tags stripped away.
     */
    public function tags(string $string): string
    {
        $regex = // Search for PHP tags.

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

        '/uis'; // End pattern.

        return preg_replace($regex, '', $string);
    }
}
