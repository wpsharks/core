<?php
/**
 * PHP userland constants.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * PHP userland constants.
 *
 * @since 160709 Userland constants.
 */
interface PhpUserlandConstants
{
    /**
     * Regex matches a valid function name.
     *
     * @since 160709 Userland constants.
     *
     * @var string Regex pattern for use in `preg_match()`.
     *
     * @see http://php.net/manual/en/functions.user-defined.php
     */
    const PHP_USERLAND_FUNCTION_REGEX_VALID = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/u';
}
