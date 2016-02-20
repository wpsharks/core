<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

/**
 * Var-related constants.
 *
 * @since 160220 Initial release.
 */
interface VarConstants
{
    /**
     * Regex matches a valid var.
     *
     * @since 160220 Initial release.
     *
     * @type string Regex pattern for use in `preg_match()`.
     *
     * @note This can be used in MySQL by outputting the following:
     *  `echo str_replace(["'", '\\x', '\\p', '\\?'], ["\\'", '\\\\x', '\\\\p', '\\\\?'], Interfaces/VarConstants::VAR_REGEX_VALID);`
     */
    const VAR_REGEX_VALID = '/^[a-z][a-z0-9_]+[a-z0-9]$/u';
}
