<?php
/**
 * Error utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Error utilities.
 *
 * @since 160710 Error utilities.
 */
class Error extends Classes\Core\Base\Core
{
    /**
     * New error instance.
     *
     * @since 160710 Error utilities.
     *
     * @param string $slug    Error slug.
     * @param string $message Error message.
     * @param mixed  $data    Error data.
     *
     * @return Classes\Core\Error Error instance.
     */
    public function __invoke(string $slug = '', string $message = '', $data = null): Classes\Core\Error
    {
        return $this->App->Di->get(Classes\Core\Error::class, compact('slug', 'message', 'data'));
    }

    /**
     * An error?
     *
     * @since 160710 Error utilities.
     *
     * @param mixed $value Value to check.
     *
     * @return bool True if `$value` is an error.
     */
    public function is($value): bool
    {
        return $value instanceof Classes\Core\Error;
    }
}
