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
 * SQL utilities.
 *
 * @since 160422 SQL utilities.
 */
class Sql extends Classes\Core\Base\Core
{
    /**
     * Quotes/escapes SQL value.
     *
     * @since 160422 SQL utils.
     *
     * @param int|float|string $value Value.
     *
     * @return string Quoted/escaped value (as a string).
     */
    public function quote($value): string
    {
        if (($Pdo = $this->c::currentPdo())) {
            return $this->c::pdoQuote($value);
        } elseif ($this->c::isWordPress()) {
            switch (gettype($value)) {
                case 'int':
                case 'integer':
                    return (string) $value;

                case 'float':
                case 'double':
                    return (string) $value;

                case 'str':
                case 'string':
                    return "'".esc_sql($value)."'";

                default: // Unexpected data type.
                    throw new Exception('Unexpected data type; unable to quote.');
            }
        }
        throw new Exception('No SQL escape handler.');
    }

    /**
     * Quotes/escapes IN clause.
     *
     * @since 160422 SQL utilities.
     *
     * @param array $array Input array to escape.
     *
     * @return string Quoted/escaped IN clause.
     */
    public function quoteIn(array $array): string
    {
        return implode(',', array_map([$this, 'quote'], $array));
    }

    /**
     * Escapes table/column names.
     *
     * @since 160422 SQL utilities.
     *
     * @param string $string Table/column name.
     *
     * @return string Escaped table/column name.
     */
    public function escapeName(string $string): string
    {
        return preg_replace('/[^a-z0-9_]+/ui', '', $string);
    }

    /**
     * Quotes/escapes table columns.
     *
     * @since 160422 SQL utilities.
     *
     * @param array  $columns    Table columns.
     * @param string $table_name Optional table name.
     *
     * @return string Quoted/escaped columns.
     */
    public function quoteColumns(array $columns, string $table_name = ''): string
    {
        $cols = ''; // Initialize.
        foreach ($columns as $_key => $_column) {
            if (is_string($_column) && $_column) {
                $cols .= ','; // Always.

                if ($table_name) { // Table prefix?
                    $cols .= '`'.$this->escapeName($table_name).'`.';
                } // If there is a table name, add a ``. prefix ↑

                $cols .= '`'.$this->escapeName($_column).'`';
            }
        } // unset($_key, $_column);
        return $this->c::mbTrim($cols, ' ,');
    }

    /**
     * Escapes order direction.
     *
     * @since 160422 SQL utilities.
     *
     * @param string $string Order direction.
     *
     * @return string Escaped order direction.
     */
    public function escapeOrder(string $string): string
    {
        $string = mb_strtoupper($string);

        return in_array($string, ['ASC', 'DESC'], true) ? $string : 'ASC';
    }

    /**
     * Quotes/escapes order-bys.
     *
     * @since 160422 SQL utilities.
     *
     * @param array  $order_bys  Order-bys.
     * @param string $table_name Optional table name.
     *
     * @return string Quoted/escaped order-bys.
     */
    public function quoteOrderBys(array $order_bys, string $table_name = ''): string
    {
        $order = ''; // Initialize.
        foreach ($order_bys as $_order_by => $_order) {
            if (is_string($_order_by) && $_order_by) {
                $order .= ',';
                if ($table_name) {
                    $order .= '`'.$this->escapeName($table_name).'`.';
                } // If table name, add a ``. prefix ↑
                $order .= '`'.$this->escapeName($_order_by).'` '.$this->escapeOrder($_order);
            }
        } // unset($_order_by, $_order);
        return $this->c::mbTrim($order, ', ');
    }
}
