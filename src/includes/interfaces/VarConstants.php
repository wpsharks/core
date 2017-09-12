<?php
/**
 * Var-related constants.
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
     */
    const VAR_REGEX_VALID = '/^[a-z0-9](?:(?:[a-z0-9]|_(?!_))*[a-z0-9])?$/u';

    /**
     * Regex matches a valid var, strictly.
     *
     * @since 17xxxx Adding strict validation.
     *
     * @type string Regex pattern for use in `preg_match()`.
     */
    const VAR_STRICT_REGEX_VALID = '/^[a-z](?:(?:[a-z0-9]|_(?!_))*[a-z0-9])?$/u';
}
