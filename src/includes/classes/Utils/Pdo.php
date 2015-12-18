<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * PDO utilities.
 *
 * @since 15xxxx Initial release.
 */
class Pdo extends Classes\AppBase
{
    /**
     * DB shards.
     *
     * @since 15xxxx
     *
     * @type array
     */
    protected $dbs;

    /**
     * Common DB info.
     *
     * @since 15xxxx
     *
     * @type array
     */
    protected $common;

    /**
     * PDO options.
     *
     * @since 15xxxx
     *
     * @type array
     */
    protected $options;

    /**
     * Current PDO instance.
     *
     * @since 15xxxx
     *
     * @type \PDO|null
     */
    protected $current_Pdo;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->options = [
            \PDO::ATTR_TIMEOUT => 5,

            \PDO::ATTR_AUTOCOMMIT       => true,
            \PDO::ATTR_EMULATE_PREPARES => false,
            // Emulation = stringified fetches (bad).

            \PDO::ATTR_CASE               => \PDO::CASE_NATURAL,
            \PDO::ATTR_ORACLE_NULLS       => \PDO::NULL_NATURAL,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,

            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            \PDO::ATTR_ERRMODE                  => \PDO::ERRMODE_EXCEPTION,
        ];
        $this->common = &$this->App->Config->db_shards['common'];
        $this->dbs    = &$this->App->Config->db_shards['dbs'];

        if (!$this->common['port'] || !$this->common['charset'] || !$this->common['username'] || !$this->dbs) {
            throw new Exception('Missing required DB config values.');
        }
        if ($this->common['ssl_enable']) {
            $this->options[\PDO::MYSQL_ATTR_SSL_CA]     = $this->common['ssl_ca'];
            $this->options[\PDO::MYSQL_ATTR_SSL_CERT]   = $this->common['ssl_crt'];
            $this->options[\PDO::MYSQL_ATTR_SSL_KEY]    = $this->common['ssl_key'];
            $this->options[\PDO::MYSQL_ATTR_SSL_CIPHER] = $this->common['ssl_cipher'];
        }
    }

    /**
     * PDO instance for a DB.
     *
     * @since 15xxxx Initial release.
     *
     * @param int|null $shard_id Shard ID explicitly.
     * @param int|null $uuid     Or, a UUID containing the shard ID.
     *
     * @return \PDO A PDO class instance.
     */
    public function get(int $shard_id = null, int $uuid = null): \PDO
    {
        if (!isset($shard_id)) {
            if (isset($uuid)) {
                $shard_id = c\uuid64_shard_id_in($uuid);
            } else {
                $shard_id = 0; // Default shard ID.
            }
        } // Now we acquire the configuration.
        $shard_db = $this->shardDbConfig($shard_id);
        $Pdo      = &$this->cacheKey(__FUNCTION__, $shard_db['host']);
        $Pdo      = $Pdo ?? new \PDO(
            'mysql:host='.$shard_db['host'].';'.
            'port='.$this->common['port'].';'.
            'charset='.$this->common['charset'],
            $this->common['username'],
            $this->common['password'],
            $this->options
        );
        $Pdo->exec('use `'.$shard_db['name'].'`');
        $this->current_Pdo = $Pdo;

        return $Pdo;
    }

    /**
     * Shard DB config properties.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $shard_id Shard ID.
     *
     * @return array Shard DB config properties.
     */
    protected function shardDbConfig(int $shard_id): array
    {
        if (!is_null($properties = &$this->cacheKey(__FUNCTION__, $shard_id))) {
            return $properties; // Cached this already.
        }
        foreach ($this->dbs as $_key => $_db) {
            if ($shard_id >= $_db['range']['from'] && $shard_id <= $_db['range']['to']) {
                return $properties = $_db['properties'];
            }
        } // unset($_key, $_db); // Houskeeping.
        throw new Exception(sprintf('Missing DB info for shard ID: `%1$s`.', $shard_id));
    }

    /**
     * Escapes table/column names.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $string Table/column name.
     *
     * @return string Escaped table/column name.
     */
    public function escName(string $string): string
    {
        return preg_replace('/[^a-z0-9_]+/ui', '', $string);
    }

    /**
     * Escapes/formats table columns.
     *
     * @since 15xxxx Initial release.
     *
     * @param array  $columns    Table columns.
     * @param string $table_name Optional table name.
     *
     * @return string Escaped/formatted columns.
     */
    public function escColumns(array $columns, string $table_name = ''): string
    {
        $cols = ''; // Initialize.
        foreach ($columns as $_key => $_column) {
            if (is_string($_column) && $_column) {
                $cols .= ','; // Always.

                if ($table_name) { // Table prefix?
                    $cols .= '`'.$this->escName($table_name).'`.';
                } // If there is a table name, add a ``. prefix ↑

                $cols .= '`'.$this->escName($_column).'`';
            }
        } // unset($_key, $_column);
        return c\mb_trim($cols, ' ,'); // Trim it up now.
    }

    /**
     * Escapes order direction.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $string Order direction.
     *
     * @return string Escaped order direction.
     */
    public function escOrder(string $string): string
    {
        $string = mb_strtoupper($string);

        return in_array($string, ['ASC', 'DESC'], true) ? $string : 'ASC';
    }

    /**
     * Escapes/formats order bys.
     *
     * @since 15xxxx Initial release.
     *
     * @param array  $order_bys  Order bys.
     * @param string $table_name Optional table name.
     *
     * @return string Escaped/formatted order bys.
     */
    public function escOrderBys(array $order_bys, string $table_name = ''): string
    {
        $order = ''; // Initialize.
        foreach ($order_bys as $_order_by => $_order) {
            if (is_string($_order_by) && $_order_by) {
                $order .= ',';
                if ($table_name) {
                    $order .= '`'.$this->escName($table_name).'`.';
                } // If table name, add a ``. prefix ↑
                $order .= '`'.$this->escName($_order_by).'` '.$this->escOrder($_order);
            }
        } // unset($_order_by, $_order);
        return c\mb_trim($order, ', ');
    }

    /**
     * Escapes/formats IN clause.
     *
     * @since 15xxxx Initial release.
     *
     * @param array  $array Input array to escape.
     * @param string $type  One of: `ints`, `floats`, `strings`.
     *
     * @return string Escaped/formatted IN clause.
     */
    public function escIn(array $array, string $type = 'strings'): string
    {
        if (!$array) { // Array empty?
            return ''; // Nothing to do.
        }
        switch (mb_strtolower($type)) {
            case 'ints': // All integers; no quotes.
                $array = array_map('intval', $array);
                $array = array_unique($array);
                return implode(',', $array);

            case 'floats': // All floats; no quotes.
                $array = array_map('floatval', $array);
                $array = array_unique($array);
                return implode(',', $array);

            case 'strings': // String must always be quoted!
                $array = array_map('strval', $array);
                $array = array_unique($array);
                if (!$this->current_Pdo) { // Must have a PDO instance.
                    throw new Exception('No PDO yet; i.e., unable to quote.');
                }
                $array = array_map([$this->current_Pdo, 'quote'], $array);
                return "'".implode("','", $array)."'";

            default: // Throw exception on unexpected data type.
                throw new Exception(sprintf('Unexpected type: `%1$s`.', $type));
        }
    }
}
