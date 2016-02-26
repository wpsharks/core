<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Dump utilities.
 *
 * @since 150424 Initial release.
 */
class Dump extends Classes\Core\Base\Core
{
    /**
     * A better `var_dump()`.
     *
     * @param mixed  $var               Any input variable/expression to dump.
     * @param bool   $return_only       Defaults to `false`; i.e., echo the dump.
     * @param int    $indent_size       Defaults to `4`; for spaced indentation.
     * @param string $indent_char       Defaults to ` `; indentation using spaces.
     * @param bool   $dump_circular_ids Defaults to a value of `false` (cleaner output).
     *                                  If `true`, circular IDs will be included in the dump.
     *                                  Helpful when trying to determine the origin of circular references.
     *
     * @return string A dump of the input `$var`.
     */
    public function __invoke($var, bool $return_only = false, int $indent_size = 2, string $indent_char = ' ', bool $dump_circular_ids = false): string
    {
        return $this->dump($var, $return_only, $indent_size, $indent_char, $dump_circular_ids);
    }

    /**
     * A better `var_dump()` (helper routine, by reference).
     *
     * @param mixed  $var                    Any input variable/expression to dump.
     * @param bool   $return_only            Defaults to `false`; i.e., echo the dump.
     * @param int    $indent_size            Defaults to `4`; for spaced indentation.
     * @param string $indent_char            Defaults to ` `; indentation using spaces.
     * @param bool   $dump_circular_ids      Defaults to a value of `false` (cleaner output).
     *                                       If `true`, circular IDs will be included in the dump.
     *                                       Helpful when trying to determine the origin of circular references.
     * @param int    $___current_indent_size Used in recursion. For internal use only.
     * @param array  $___nested_circular_ids Used in recursion. For internal use only.
     * @param bool   $___recursion           Tracks recursion. For internal use only.
     *
     * @return string A dump of the input `$var` (always in string format).
     *
     * @note This method has built-in protection against circular references.
     *
     * @note This was benchmarked a few times against PHP's own `var_dump()`, and also against `print_r()`.
     *    This routine is NOT faster than `var_dump()`, but it is MUCH faster than `print_r()`.
     *
     * @note This routine MUST be very careful that it does NOT write to any variables.
     *    Writing to a variable (or to a variable reference) could cause damage in other routines.
     */
    protected function dump(
        &$var,
        bool $return_only,
        int $indent_size,
        string $indent_char,
        bool $dump_circular_ids,
        int $___current_indent_size = 0,
        array $___nested_circular_ids = [],
        bool $___recursion = false
    ): string {
        if (!$___recursion) {
            $indent_size = max(1, $indent_size);
        }
        switch (($type = $display_type = gettype($var))) {

            case 'object': // Iterates each object property.
            case 'array': // Or, each array key (if this is an array).
                $longest_nested_key_prop_length = 0;
                $nested_dumps                   = [];

                $dump_indent_size        = $___current_indent_size + ($indent_size * 1);
                $nested_dump_indent_size = $___current_indent_size + ($indent_size * 2);

                $current_indents     = str_repeat($indent_char, $___current_indent_size);
                $dump_indents        = $current_indents.str_repeat($indent_char, $dump_indent_size);
                $nested_dump_indents = $current_indents.str_repeat($indent_char, $nested_dump_indent_size);

                $opening_encap      = $type === 'object' ? '{' : '[';
                $closing_encap      = $type === 'object' ? '}' : ']';
                $key_prop_value_sep = $type === 'object' ? ': ' : ' => ';

                if ($type === 'object') {
                    $display_type = ($dump_circular_ids ? spl_object_hash($var).' ' : '').get_class($var).' object';
                } elseif ($type === 'array') {
                    $display_type = $dump_circular_ids ? sha1(serialize($var)) : '';
                }
                $var_dump = $display_type."\n".$dump_indents.$opening_encap."\n";

                foreach ($type === 'object' && method_exists($var, '__debugInfo')
                        ? $var->__debugInfo() // Use magic properties.
                        : $var as $_nested_key_prop => $_nested_value) {
                    // See: <http://php.net/manual/en/language.oop5.magic.php#object.debuginfo>
                    // Do NOT use `&`. Some iterators CANNOT be iterated by reference.
                    if (is_string($_nested_key_prop)) {
                        $_nested_key_prop = "'".$_nested_key_prop."'";
                    }
                    $_nested_key_prop_length = mb_strlen((string) $_nested_key_prop);
                    if ($_nested_key_prop_length > $longest_nested_key_prop_length) {
                        $longest_nested_key_prop_length = $_nested_key_prop_length;
                    }
                    switch (($_nested_type = $_nested_display_type = gettype($_nested_value))) {
                        case 'int':
                        case 'integer':
                            $_nested_display_type            = 'int';
                            $nested_dumps[$_nested_key_prop] = (string) $_nested_value;
                            break; // Break switch.

                        case 'real':
                        case 'double':
                        case 'float':
                            $_nested_display_type            = 'float';
                            $nested_dumps[$_nested_key_prop] = (string) $_nested_value;
                            break; // Break switch.

                        case 'bool':
                        case 'boolean':
                            $_nested_display_type            = 'bool';
                            $nested_dumps[$_nested_key_prop] = ($_nested_value) ? 'true' : 'false';
                            break; // Break switch.

                        case 'string':
                            $nested_dumps[$_nested_key_prop] = "'".$_nested_value."'";
                            break; // Break switch.

                        case 'object': // Recurses into object values.
                            $_nested_circular_id_key = spl_object_hash($_nested_value);
                            $_nested_display_type    = ($dump_circular_ids ? $_nested_circular_id_key.' ' : '').get_class($_nested_value).' object';

                            if (isset($___nested_circular_ids[$_nested_circular_id_key])) {
                                $nested_dumps[$_nested_key_prop] = $_nested_display_type.'{} *circular*';
                            } elseif (($___nested_circular_ids[$_nested_circular_id_key] = -1)
                                && ($_nested_dump = $this->dump(
                                    $_nested_value,
                                    true,
                                    $indent_size,
                                    $indent_char,
                                    $dump_circular_ids,
                                    $dump_indent_size,
                                    $___nested_circular_ids,
                                    true
                                ))) {
                                $nested_dumps[$_nested_key_prop] = $_nested_dump;
                            } else {
                                $nested_dumps[$_nested_key_prop] = $_nested_display_type.'{}';
                            }
                            unset($_nested_display_type, $_nested_circular_id_key, $_nested_dump);

                            break; // Break switch.

                        case 'array': // Recurses into array values.
                            $_nested_circular_id_key = sha1(serialize($_nested_value));
                            $_nested_display_type .= $dump_circular_ids ? $_nested_circular_id_key : '';

                            if (isset($___nested_circular_ids[$_nested_circular_id_key])) {
                                $nested_dumps[$_nested_key_prop] = $_nested_display_type.'[] *circular*';
                            } elseif (($___nested_circular_ids[$_nested_circular_id_key] = -1)
                                    && ($_nested_dump = $this->dump(
                                        $_nested_value,
                                        true,
                                        $indent_size,
                                        $indent_char,
                                        $dump_circular_ids,
                                        $dump_indent_size,
                                        $___nested_circular_ids,
                                        true
                                    ))) {
                                $nested_dumps[$_nested_key_prop] = $_nested_dump;
                            } else {
                                $nested_dumps[$_nested_key_prop] = $_nested_display_type.'[]';
                            }
                            unset($_nested_display_type, $_nested_circular_id_key, $_nested_dump);
                            break; // Break switch.

                        case 'null':
                        case 'NULL':
                            $_nested_display_type            = 'null';
                            $nested_dumps[$_nested_key_prop] = 'null';
                            break; // Break switch.

                        case 'resource':
                            $nested_dumps[$_nested_key_prop] = '['.(string) $_nested_value.']';
                            break; // Break switch.

                        case 'unknown':
                        case 'unknown type':
                            $_nested_display_type            = 'unknown';
                            $nested_dumps[$_nested_key_prop] = (string) $_nested_value;
                            break; // Break switch.

                        default: // Default case handler.
                            throw new Exception(sprintf('Unexpected data type: `%1$s`.', $_nested_type));
                    }
                }
                unset($_nested_key_prop, $_nested_value, $_nested_type, $_nested_key_prop_length);

                if ($nested_dumps) {
                    foreach ($nested_dumps as $_nested_key_prop => $_nested_dump) {
                        $_aligning_spaces = str_repeat(' ', $longest_nested_key_prop_length - mb_strlen((string) $_nested_key_prop));
                        $var_dump .= $nested_dump_indents.$_nested_key_prop.$_aligning_spaces.$key_prop_value_sep.$_nested_dump."\n";
                    }
                    unset($_nested_key_prop, $_nested_dump, $_aligning_spaces);

                    $var_dump = $var_dump.$dump_indents.$closing_encap;
                } else {
                    $var_dump = $this->c::mbRTrim($var_dump, "\n".$indent_char.$opening_encap).$opening_encap.$closing_encap;
                }
                break; // Break switch.

            // Everything else is MUCH simpler to handle.

            case 'int':
            case 'integer':
                $display_type = 'int';
                $var_dump     = (string) $var;
                break; // Break switch.

            case 'real':
            case 'double':
            case 'float':
                $display_type = 'float';
                $var_dump     = '(float) '.(string) $var;
                break; // Break switch.

            case 'bool':
            case 'boolean':
                $display_type = 'bool';
                $var_dump     = $var ? 'true' : 'false';
                break; // Break switch.

            case 'string':
                $var_dump = "'".$var."'";
                break; // Break switch.

            case 'null':
            case 'NULL':
                $display_type = 'null';
                $var_dump     = 'null';
                break; // Break switch.

            case 'resource':
                $var_dump = '['.(string) $var.']';
                break; // Break switch.

            case 'unknown':
            case 'unknown type':
                $display_type = 'unknown';
                $var_dump     = (string) $var;
                break; // Break switch.

            default: // Default case handler.
                throw new Exception(sprintf('Unexpected data type: `%1$s`.', $type));
        }
        if (!$return_only) {
            echo $var_dump."\n";
        }
        return $var_dump;
    }
}
