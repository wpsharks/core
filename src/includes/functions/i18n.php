<?php
declare (strict_types = 1);

if (!defined('ABSPATH') && !defined('WPINC')) {
    if (!function_exists('__')) {
        /**
         * @since 160223 Polyfill.
         */
        function __(string $string): string
        {
            return $string;
        }
    }
    if (!function_exists('_x')) {
        /**
         * @since 160223 Polyfill.
         */
        function _x(string $string, string $context): string
        {
            return $string;
        }
    }
    if (!function_exists('_n')) {
        /**
         * @since 160223 Polyfill.
         */
        function _n(string $singular, string $plural, float $count): string
        {
            return $count !== 1 && $count !== (float) 1 ? $plural : $singular;
        }
    }
    if (!function_exists('_nx')) {
        /**
         * @since 160223 Polyfill.
         */
        function _nx(string $singular, string $plural, float $count, string $context): string
        {
            return $count !== 1 && $count !== (float) 1 ? $plural : $singular;
        }
    }
}
