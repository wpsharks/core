<?php
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
 * Escape utils.
 *
 * @since 150424 Escape utils.
 */
class Escape extends Classes\Core\Base\Core
{
    /**
     * Escape HTML markup (other).
     *
     * @since 151122 Escapes.
     *
     * @param mixed Input value.
     *
     * @return string|array|object Output value.
     */
    public function html($value)
    {
        return $this->App->Utils->©HtmlEntities->encode($value);
    }

    /**
     * Escape `<textarea>` value.
     *
     * @since 151122 Escapes.
     *
     * @param mixed Input value.
     *
     * @return string|array|object Output value.
     */
    public function textarea($value)
    {
        return $this->App->Utils->©HtmlEntities->encode($value);
    }

    /**
     * Escape `attr=""` (other).
     *
     * @since 151122 Escapes.
     *
     * @param mixed Input value.
     *
     * @return string|array|object Output value.
     */
    public function attr($value)
    {
        return $this->App->Utils->©HtmlEntities->encode($value);
    }

    /**
     * Escape URL in `attr=""`.
     *
     * @since 151122 Escapes.
     *
     * @param mixed Input value.
     *
     * @return string|array|object Output value.
     */
    public function url($value)
    {
        return $this->App->Utils->©HtmlEntities->encode($value);
    }

    /**
     * Escape shell arg(s).
     *
     * @since 160110 Escapes.
     *
     * @param mixed Input value.
     *
     * @return string|array|object Output value.
     */
    public function shellArg($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->shell_arg($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        return escapeshellarg($string);
    }

    // NOTE: SQL-related escapes are in the `Sql` class.
    // NOTE: Regex-related escapes are in the `RegexEscape` class.
}
