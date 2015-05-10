<?php
namespace WebSharks\Core\Traits;

/**
 * Var dump utilities.
 *
 * @since 150424 Initial release.
 */
trait VarDumpUtils
{
    /**
     * A better `var_dump()`.
     *
     * @param mixed $var               Any input variable/expression to dump.
     * @param bool  $echo              Defaults to `FALSE`; i.e., do not echo.
     * @param int   $indent_size       Defaults to `4`; for spaced indentation.
     * @param int   $indent_char       Defaults to ` `; indentation using spaces.
     * @param bool  $dump_circular_ids Defaults to a value of `FALSE` (cleaner output).
     *                                 If `TRUE`, circular IDs for will be included in the dump.
     *                                 Helpful when trying to determine the origin of circular references.
     *
     * @return string A dump of the input `$var`.
     *
     * @note This method has built-in protection against circular references.
     *
     * @note This was benchmarked a few times against PHP's own `var_dump()`, and also against `print_r()`.
     *    This routine is NOT faster than `var_dump()`, but it is MUCH faster than `print_r()`.
     *
     * @note This routine MUST be very careful that it does NOT write to any variables.
     *    Writing to a variable (or to a variable reference) could cause damage in other routines.
     */
    protected function varDump($var, $echo = false, $indent_size = 4, $indent_char = ' ', $dump_circular_ids = false)
    {
        return $this->varDumper($var, $echo, $indent_size, $dump_circular_ids);
    }

    /**
     * A better `var_dump()` (helper routine, by reference).
     *
     * @param mixed $var                    Any input variable by reference.
     * @param bool  $echo                   Defaults to `FALSE`; i.e., do not echo.
     * @param int   $indent_size            Defaults to `4`; for spaced indentation.
     * @param int   $indent_char            Defaults to ` `; indentation using spaces.
     * @param bool  $dump_circular_ids      Defaults to a value of `FALSE` (cleaner output).
     *                                      If `TRUE`, circular IDs for will be included in the dump.
     *                                      Helpful when trying to determine the origin of circular references.
     * @param int   $___current_indent_size Used in recursion. This is for internal use only.
     * @param array $___nested_circular_ids Used in recursion. For internal use only.
     * @param bool  $___recursion           Tracks recursion. For internal use only.
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
    protected function varDumper(
        &$var,
        $echo = false,
        $indent_size = 4,
        $indent_char = ' ',
        $dump_circular_ids = false,
        $___current_indent_size = 0,
        $___nested_circular_ids = array(),
        $___recursion = false
    ) {
        if (!$___recursion) {
            $indent_size = max(1, $indent_size);
            $indent_char = (string) $indent_char;
        }
        switch (($type = $real_type = strtolower(gettype($var)))) {

            case 'object': // Iterates each object property.
            case 'array': // Or, each array key (if this is an array).

                $longest_nested_key_prop_length = 0;
                $nested_dumps                   = array();

                $dump_indent_size        = $___current_indent_size + ($indent_size * 1);
                $nested_dump_indent_size = $___current_indent_size + ($indent_size * 2);

                $current_indents     = str_repeat($indent_char, $___current_indent_size);
                $dump_indents        = $current_indents.str_repeat($indent_char, $dump_indent_size);
                $nested_dump_indents = $current_indents.str_repeat($indent_char, $nested_dump_indent_size);

                $opening_encap          = $type === 'object' ? '{' : '(';
                $closing_encap          = $type === 'object' ? '}' : ')';
                $opening_key_prop_encap = $type === 'object' ? '{' : '[';
                $closing_key_prop_encap = $type === 'object' ? '}' : ']';
                $key_prop_value_sep     = ' => '; // Same for object/array.

                if ($type === 'object') {
                    $real_type = 'object'.($dump_circular_ids ? '::'.spl_object_hash($var) : '').'::'.get_class($var);
                } elseif ($type === 'array') {
                    $real_type = 'array'.($dump_circular_ids ? '::'.md5(serialize($var)) : '');
                }
                $var_dump = $real_type."\n".$dump_indents.$opening_encap."\n";

                foreach ($var as $_nested_key_prop => $_nested_value /* Not by reference. */) {
                    // Do NOT use `&`. Some iterators CANNOT be iterated by reference.
                    $_nested_type = strtolower(gettype($_nested_value));

                    if (is_string($_nested_key_prop)) {
                        $_nested_key_prop = "'".$_nested_key_prop."'";
                    }
                    $_nested_key_prop_length = strlen((string) $_nested_key_prop);
                    if ($_nested_key_prop_length > $longest_nested_key_prop_length) {
                        $longest_nested_key_prop_length = $_nested_key_prop_length;
                    }
                    switch ($_nested_type) {
                        case 'integer':
                            $nested_dumps[$_nested_key_prop] = (string) $_nested_value;
                            break; // Break switch.

                        case 'real': // Alias for `float` type.
                        case 'double': // Alias for `float` type.
                        case 'float': // Standardized as `float` type.
                            $_nested_type                    = 'float';
                            $nested_dumps[$_nested_key_prop] = (string) $_nested_value;
                            break; // Break switch.

                        case 'string':
                            $nested_dumps[$_nested_key_prop] = "'".$_nested_value."'";
                            break; // Break switch.

                        case 'boolean':
                            $nested_dumps[$_nested_key_prop] = ($_nested_value) ? 'true' : 'false';
                            break; // Break switch.

                        case 'resource':
                            $nested_dumps[$_nested_key_prop] = '['.(string) $_nested_value.']';
                            break; // Break switch.

                        case 'object': // Recurses into object values.

                            $_nested_circular_id_key = spl_object_hash($_nested_value);
                            $_nested_real_type       = $_nested_type.($dump_circular_ids ? '::'.$_nested_circular_id_key : '').'::'.get_class($_nested_value);

                            if (isset($___nested_circular_ids[$_nested_circular_id_key])) {
                                $nested_dumps[$_nested_key_prop] = $_nested_real_type.'{} *circular*';
                            } elseif (($___nested_circular_ids[$_nested_circular_id_key] = -1)
                                && ($_nested_dump = $this->varDumper(
                                    $_nested_value,
                                    $this::NO_ECHO,
                                    $indent_size,
                                    $indent_char,
                                    $dump_circular_ids,
                                    $dump_indent_size,
                                    $___nested_circular_ids,
                                    true
                                ))) {
                                $nested_dumps[$_nested_key_prop] = $_nested_dump;
                            } else {
                                $nested_dumps[$_nested_key_prop] = $_nested_real_type.'{}';
                            }
                            unset($_nested_real_type, $_nested_circular_id_key, $_nested_dump);
                            break; // Break switch.

                        case 'array': // Recurses into array values.

                            $_nested_circular_id_key = md5(serialize($_nested_value));
                            $_nested_real_type       = $_nested_type.(($dump_circular_ids) ? '::'.$_nested_circular_id_key : '');

                            if (isset($___nested_circular_ids[$_nested_circular_id_key])) {
                                $nested_dumps[$_nested_key_prop] = $_nested_real_type.'() *circular*';
                            } elseif (($___nested_circular_ids[$_nested_circular_id_key] = -1)
                                    && ($_nested_dump = $this->varDumper(
                                        $_nested_value,
                                        $this::NO_ECHO,
                                        $indent_size,
                                        $indent_char,
                                        $dump_circular_ids,
                                        $dump_indent_size,
                                        $___nested_circular_ids,
                                        true
                                    ))) {
                                $nested_dumps[$_nested_key_prop] = $_nested_dump;
                            } else {
                                $nested_dumps[$_nested_key_prop] = $_nested_real_type.'()';
                            }
                            unset($_nested_real_type, $_nested_circular_id_key, $_nested_dump);
                            break; // Break switch.

                        case 'null':
                            $nested_dumps[$_nested_key_prop] = 'null';
                            break; // Break switch.

                        default: // Default case handler.
                            $nested_dumps[$_nested_key_prop] = (string) $_nested_value;
                            break; // Break switch.
                    }
                }
                unset($_nested_key_prop, $_nested_value, $_nested_type, $_nested_key_prop_length);

                if (!empty($nested_dumps)) {
                    foreach ($nested_dumps as $_nested_key_prop => $_nested_dump) {
                        $_aligning_spaces = str_repeat(' ', $longest_nested_key_prop_length - strlen($_nested_key_prop));
                        $var_dump .= $nested_dump_indents.$opening_key_prop_encap.$_nested_key_prop.$closing_key_prop_encap.$_aligning_spaces.$key_prop_value_sep.$_nested_dump."\n";
                    }
                    unset($_nested_key_prop, $_nested_dump, $_aligning_spaces);

                    $var_dump = $var_dump.$dump_indents.$closing_encap;
                } else {
                    $var_dump = rtrim($var_dump, "\n".$indent_char.$opening_encap).$opening_encap.$closing_encap;
                }
                break; // Break switch.

            // Everything else is MUCH simpler to handle.

            case 'integer':
                $var_dump = (string) $var;
                break; // Break switch.

            case 'real': // Alias for `float` type.
            case 'double': // Alias for `float` type.
            case 'float': // Standardized as `float` type.
                $real_type = 'float'; // Real type.
                $var_dump  = (string) $var;
                break; // Break switch.

            case 'string':
                $var_dump = "'".$var."'";
                break; // Break switch.

            case 'boolean':
                $var_dump = ($var) ? 'true' : 'false';
                break; // Break switch.

            case 'resource':
                $var_dump = '['.(string) $var.']';
                break; // Break switch.

            case 'null':
                $var_dump = 'null';
                break; // Break switch.

            default: // Default case handler.
                $var_dump = (string) $var;
                break; // Break switch.
        }
        if ($echo) {
            echo $var_dump."\n";
        }
        return $var_dump; // Always return output value.
    }
}
