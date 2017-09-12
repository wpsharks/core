<?php
/**
 * PHP userland utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * PHP userland utils.
 *
 * @since 160709 Userland constants.
 */
class PhpUserland extends Classes\Core\Base\Core implements Interfaces\PhpUserlandConstants
{
    /**
     * Is a valid function name?
     *
     * @since 160709 Userland constants.
     *
     * @param string $function A PHP function.
     *
     * @return bool True if the function has a valid name.
     */
    public function isValidFunctionName(string $function): bool
    {
        return $function && preg_match($this::PHP_USERLAND_FUNCTION_REGEX_VALID, $function);
    }
}
