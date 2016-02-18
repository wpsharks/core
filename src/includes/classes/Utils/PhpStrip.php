<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * PHP strip utilities.
 *
 * @since 150424 Initial release.
 */
class PhpStrip extends Classes\AppBase
{
    /**
     * Strips PHP tags.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function tags($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->tags($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $regex = // Search for PHP tags.
            '/'.// Open pattern delimiter.
            '(?:'.// Any of these.

                '\<\?php.*?\?\>|\<\?\=.*?\?\>|\<\?.*?\?\>|\<%.*?%\>'.
                '|\<script\s+[^>]*?language\s*\=\s*(["\'])?php\\1[^>]*\>.*?\<\s*\/\s*script\s*\>'.

            ')'.// Close 'any of these'.
            '/uis'; // End pattern.
        return preg_replace($regex, '', $string);
    }
}
