<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Traits;

/**
 * Slug-related constants.
 *
 * @since 150424 Initial release.
 */
interface SlugConstants
{
    /**
     * Regex matches a valid slug.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex pattern for use in `preg_match()`.
     *
     * @note This can be used in MySQL by outputting the following:
     *  `echo str_replace(["'", '\\x', '\\p', '\\?'], ["\\'", '\\\\x', '\\\\p', '\\\\?'], Interfaces/SlugConstants::SLUG_REGEX_VALID);`
     */
    const SLUG_REGEX_VALID = '/^[a-z][a-z0-9\-]+[a-z0-9]$/u';
}
