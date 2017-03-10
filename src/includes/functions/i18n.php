<?php
/**
 * i18n functions.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);

if (!defined('ABSPATH') && !defined('WPINC')) {
    if (!function_exists('__')) {
        /**
         * Polyfill.
         *
         * @since 160223 Polyfill.
         *
         * @param string $string String.
         */
        function __(string $string): string
        {
            return $string;
        }
    }
    if (!function_exists('_x')) {
        /**
         * Polyfill.
         *
         * @since 160223 Polyfill.
         *
         * @param string $string  String.
         * @param string $context Context.
         */
        function _x(string $string, string $context): string
        {
            return $string;
        }
    }
    if (!function_exists('_n')) {
        /**
         * Polyfill.
         *
         * @since 160223 Polyfill.
         *
         * @param string    $singular String.
         * @param string    $plural   String.
         * @param float|int $count    Count.
         */
        function _n(string $singular, string $plural, float $count): string
        {
            return $count !== 1 && $count !== (float) 1 ? $plural : $singular;
        }
    }
    if (!function_exists('_nx')) {
        /**
         * Polyfill.
         *
         * @since 160223 Polyfill.
         *
         * @param string    $singular String.
         * @param string    $plural   String.
         * @param float|int $count    Count.
         * @param string    $context  Context.
         */
        function _nx(string $singular, string $plural, float $count, string $context): string
        {
            return $count !== 1 && $count !== (float) 1 ? $plural : $singular;
        }
    }
}
