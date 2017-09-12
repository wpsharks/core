<?php
/**
 * SQL utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * SQL utilities.
 *
 * @since 160422 SQL utilities.
 */
class Sql extends Classes\Core\Base\Core
{
    /**
     * Exists?
     *
     * @since 17xxxx
     *
     * @type bool
     */
    protected $pdo_exists;

    /**
     * Constructor.
     *
     * @since 160719 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->pdo_exists = class_exists('PDO');
    }

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
        if ($this->pdo_exists && $this->c::currentPdo()) {
            return $this->c::pdoQuote($value);
        }
        switch (gettype($value)) {
            case 'int':
            case 'integer':
                return (string) $value;

            case 'float':
            case 'double':
                return (string) $value;

            case 'str':
            case 'string':
                if ($this->c::isWordPress()) {
                    return "'".esc_sql($value)."'";
                }
            // Else fall through to exception.
            // no break
            default: // Unexpected data type.
                throw $this->c::issue('No SQL escape handler.');
        }
    }

    /**
     * Quotes/escapes IN values.
     *
     * @since 160422 SQL utils.
     *
     * @param array $values Values.
     *
     * @return string Quoted/escaped IN values.
     */
    public function quoteIn(array $values): string
    {
        return implode(',', array_map([$this, 'quote'], $values));
    }

    /**
     * Quotes/escapes table.column.
     *
     * @since 160422 SQL utils.
     *
     * @param string $column Column.
     * @param string $table  Table name.
     *
     * @return string Quotes/escapes table.column.
     */
    public function quoteColumn(string $column, string $table = ''): string
    {
        $quoted = ''; // Initialize.

        if ($table) { // Table prefix?
            $quoted .= '`'.$this->escapeName($table).'`.';
        } // If there's a table add ``. prefix.

        return $quoted .= '`'.$this->escapeName($column).'`';
    }

    /**
     * Quotes/escapes table.columns.
     *
     * @since 160422 SQL utils.
     *
     * @param array  $columns Columns.
     * @param string $table   Table name.
     *
     * @return string Quoted/escaped table.columns.
     */
    public function quoteColumns(array $columns, string $table = ''): string
    {
        $quoted = ''; // Initialize.

        foreach ($columns as $_column) {
            if ($_column && is_string($_column)) {
                $quoted .= ','.$this->quoteColumn($_column, $table);
            }
        } // unset($_column); // Housekeeping.

        return $this->c::mbTrim($quoted, ' ,');
    }

    /**
     * Quotes/escapes values.
     *
     * @since 17xxxx SQL utils.
     *
     * @param array $values Values.
     *
     * @return string Quoted/escaped values.
     */
    public function quoteValues(array $values): string
    {
        return implode(',', array_map([$this, 'quote'], $values));
    }

    /**
     * Quotes/escapes (table.column) (sets).
     *
     * @since 17xxxx SQL utils.
     *
     * @param array  $sets  Sets.
     * @param string $table Table name.
     *
     * @return string Quoted/escaped (table.column) (sets).
     */
    public function quoteCvSets(array $sets, string $table = ''): string
    {
        $quoted = ''; // Initialize.

        foreach ($sets as $_set) {
            if (!is_array($_set)) {
                continue; // Not possible.
            }
            if (!$quoted) { // Establish columns.
                $quoted .= '('.$this->quoteColumns(array_keys($_set), $table).')';
                $quoted .= 'VALUES('.$this->quoteValues($_set).')';
            } else {
                $quoted .= ',('.$this->quoteValues($_set).')';
            }
        } // unset($_set); // Housekeeping.

        return $this->c::mbTrim($quoted, ' ,');
    }

    /**
     * Quotes/escapes table.column=value pairs.
     *
     * @since 17xxxx SQL utils.
     *
     * @param array  $pairs Pairs.
     * @param string $table Table name.
     *
     * @return string Quoted/escaped table.column=value pairs.
     */
    public function quoteCvPairs(array $pairs, string $table = ''): string
    {
        $quoted = ''; // Initialize.

        foreach ($pairs as $_column => $_value) {
            if ($_column && is_string($_column)) {
                $quoted .= ','.$this->quoteColumn($_column, $table);
                $quoted .= '='.$this->quote($_value);
            }
        } // unset($_key, $_column); // Housekeeping.

        return $this->c::mbTrim($quoted, ' ,');
    }

    /**
     * Escapes an SQL name.
     *
     * @since 160422 SQL utils.
     *
     * @param string $name SQL name.
     *
     * @return string Escaped SQL name.
     */
    public function escapeName(string $name): string
    {
        return preg_replace('/[^a-z0-9_]+/ui', '', $name);
    }

    /**
     * Escapes order direction.
     *
     * @since 160422 SQL utils.
     *
     * @param string $order Order direction.
     *
     * @return string Escaped order direction.
     */
    public function escapeOrder(string $order): string
    {
        $string = mb_strtoupper($order);
        return in_array($order, ['ASC', 'DESC'], true) ? $order : 'ASC';
    }

    /**
     * Quotes/escapes order-bys.
     *
     * @since 160422 SQL utils.
     *
     * @param array  $order_bys Order-bys.
     * @param string $table     Table name.
     *
     * @return string Quoted/escaped order-bys.
     */
    public function quoteOrderBys(array $order_bys, string $table = ''): string
    {
        $quoted = ''; // Initialize.

        foreach ($order_bys as $_order_by => $_order) {
            if ($_order_by && is_string($_order_by)) {
                $quoted .= ','; // Always.

                if ($table) { // Table prefix?
                    $quoted .= '`'.$this->escapeName($table).'`.';
                } // If table name, add a ``. prefix.

                $quoted .= '`'.$this->escapeName($_order_by).'` '.$this->escapeOrder($_order);
            }
        } // unset($_order_by, $_order);

        return $this->c::mbTrim($quoted, ', ');
    }
}
