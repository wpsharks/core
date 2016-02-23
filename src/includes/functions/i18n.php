<?php
declare (strict_types = 1);

if (!function_exists('__')) {
    /**
     * @since 160223 Polyfill.
     */
    function __(string $string): string
    {
        return $string;
    }
}
