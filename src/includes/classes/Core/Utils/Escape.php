<?php
/**
 * Escape utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
     * Escape single-quote.
     *
     * @since 160708 Quotes.
     *
     * @param mixed $value Input value.
     *
     * @return string|array|object Output value.
     */
    public function sq($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->sq($_value);
            } // unset($_key, $_value);
            return $value;
        }
        return str_replace("'", "\\'", (string) $value);
    }

    /**
     * Single-quote encapsulation.
     *
     * @since 160708 Quotes.
     *
     * @param mixed $value         Input value.
     * @param bool  $maybe_use_sld Maybe; i.e., detect string-literals?
     *
     * @return string|array|object Output value.
     *
     * --- When `$maybe_use_sld` = `true` ...
     *
     * String literals are detected automatically; e.g., `'an already-quoted string'`.
     *
     * @note This trims existing 'single' and/or "double" quoted encapsulations before quoting again.
     * For that reason, escaped quotes inside an already-quoted string are always escaped again.
     * So if you're going to quote a string literal, you should NOT escape inner quotes.
     *
     * e.g., `'Here's a good example, don't escape inner quotes.'`.
     *
     * @note This function is used by our Simple Expression parser.
     */
    public function singleQuote($value, bool $maybe_use_sld = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->singleQuote($_value);
            } // unset($_key, $_value);
            return $value;
        }
        $string = (string) $value;

        if ($maybe_use_sld) {
            if (!isset($string[0])) {
                return "''"; // Empty string.
            } elseif ($string === "''" || $string === '""') {
                return "''"; // Empty string.
            } elseif (mb_strpos($string, "'") === 0 && mb_substr($string, -1) === "'") {
                return "'".str_replace("'", "\\'", mb_substr($string, 1, -1))."'";
            } elseif (mb_strpos($string, '"') === 0 && mb_substr($string, -1) === '"') {
                return "'".str_replace("'", "\\'", mb_substr($string, 1, -1))."'";
            } else {
                return "'".str_replace("'", "\\'", $string)."'"; // Defaul behavior.
            }
        } else {
            return "'".str_replace("'", "\\'", $string)."'"; // Defaul behavior.
        }
    }

    /**
     * Escape double-quote.
     *
     * @since 160708 Quotes.
     *
     * @param mixed $value Input value.
     *
     * @return string|array|object Output value.
     */
    public function dq($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->sq($_value);
            } // unset($_key, $_value);
            return $value;
        }
        return str_replace('"', '\\"', (string) $value);
    }

    /**
     * Double-quote encapsulation.
     *
     * @since 160708 Quotes.
     *
     * @param mixed $value   Input value.
     * @param bool  $for_csv Changes quote-style for CSV compat.
     *                       CSV quoting requires the use of a double-quote as the escape char.
     *
     * @return string|array|object Output value.
     *
     * @WARNING Double-quotes, in most languages, result in evaluation.
     */
    public function doubleQuote($value, bool $for_csv = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->doubleQuote($_value);
            } // unset($_key, $_value);
            return $value;
        }
        return '"'.str_replace('"', $for_csv ? '""' : '\\"', (string) $value).'"';
    }

    /**
     * Escape HTML markup.
     *
     * @since 151122 Escapes.
     *
     * @param mixed $value Input value.
     *
     * @return string|array|object Output value.
     */
    public function html($value)
    {
        return $this->App->Utils->©HtmlEntities->encode($value);
    }

    /**
     * Escape HTML chars.
     *
     * @since 170124.74961 Escapes.
     *
     * @param mixed $value Input value.
     *
     * @return string|array|object Output value.
     */
    public function htmlChars($value)
    {
        return $this->App->Utils->©HtmlEntities->encode($value, false, ENT_HTML5 | ENT_NOQUOTES | ENT_SUBSTITUTE);
    }

    /**
     * Escape `<textarea>` value.
     *
     * @since 151122 Escapes.
     *
     * @param mixed $value Input value.
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
     * @param mixed $value Input value.
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
     * @param mixed $value Input value.
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
     * @param mixed $value Input value.
     *
     * @return string|array|object Output value.
     */
    public function shellArg($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->shellArg($_value);
            } // unset($_key, $_value);
            return $value;
        }
        return escapeshellarg((string) $value);
    }

    // NOTE: SQL-related escapes are in the `Sql` class.
    // NOTE: Regex-related escapes are in the `RegexEscape` class.
}
