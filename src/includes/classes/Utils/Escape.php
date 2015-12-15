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
class Escape extends Classes\AbsBase
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
        return $this->Utils->HtmlEntities->encode($value);
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
        return $this->Utils->HtmlEntities->encode($value);
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
        return $this->Utils->HtmlEntities->encode($value);
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
        return $this->Utils->HtmlEntities->encode($value);
    }

    // NOTE: SQL-related escapes are in the PDO class.
}
