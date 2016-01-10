<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Escape utils.
 *
 * @since 15xxxx Escape utils.
 */
class Escape extends Classes\AppBase
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
        return $this->App->Utils->HtmlEntities->encode($value);
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
    public function textarea($value): string
    {
        return $this->App->Utils->HtmlEntities->encode($value);
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
    public function attr($value): string
    {
        return $this->App->Utils->HtmlEntities->encode($value);
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
    public function url($value): string
    {
        return $this->App->Utils->HtmlEntities->encode($value);
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
    public function shell_arg($value): string
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

    // NOTE: SQL-related escapes are in the PDO class.
}
