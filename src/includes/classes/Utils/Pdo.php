<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
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
     * DB hosts.
     *
     * @since 15xxxx
     *
     * @type array
     */
    protected $hosts;

    /**
     * DB shards.
     *
     * @since 15xxxx
     *
     * @type array
     */
    protected $shards;

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
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->hosts  = &$this->App->Config->mysql_db['hosts'];
        $this->shards = &$this->App->Config->mysql_db['shards'];
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
                $shard_id = 0; // Default.
            }
        } // Now we acquire the configuration.

        $shard_db = $this->shardDbConfig($shard_id);

        if (empty($this->hosts[$shard_db['host']])) {
            throw new Exception(sprintf('Missing host for shard ID: `%1$s`.', $shard_id));
        }
        $shard_db_host         = &$this->hosts[$shard_db['host']]; // By reference (save memory).
        $shard_db_host['name'] = &$shard_db['host']; // Copy this over for clarity.

        // Check the cache. Already connected to this DB host?

        if (($Pdo = &$this->cacheKey(__FUNCTION__, $shard_db_host['name']))) {
            $Pdo->exec('use `'.$shard_db['name'].'`');
            $this->current_Pdo = $Pdo;
            return $Pdo;
        }
        // Otherwise, we need to establish a new connection.

        $shard_db_host['options'] = [
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
        if ($shard_db_host['charset']) { // Use a specific charset?
            $shard_db_host['options'][\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES \''.$shard_db_host['charset'].'\'';

            if ($shard_db_host['collate']) { // Also a specific collation?
                $shard_db_host['options'][\PDO::MYSQL_ATTR_INIT_COMMAND] .= ' COLLATE \''.$shard_db_host['collate'].'\'';
            }
        }
        if ($shard_db_host['ssl_enable'] && $shard_db_host['ssl_key'] && $shard_db_host['ssl_crt'] && $shard_db_host['ssl_ca'] && $shard_db_host['ssl_cipher']) {
            $shard_db_host['options'][\PDO::MYSQL_ATTR_SSL_KEY]    = $shard_db_host['ssl_key'];
            $shard_db_host['options'][\PDO::MYSQL_ATTR_SSL_CERT]   = $shard_db_host['ssl_crt'];
            $shard_db_host['options'][\PDO::MYSQL_ATTR_SSL_CA]     = $shard_db_host['ssl_ca'];
            $shard_db_host['options'][\PDO::MYSQL_ATTR_SSL_CIPHER] = $shard_db_host['ssl_cipher'];
        }
        $Pdo = new \PDO(// By reference. We are caching this connection.
            'mysql:host='.$shard_db_host['name'].';port='.$shard_db_host['port'],
            $shard_db_host['username'], // Connection username.
            $shard_db_host['password'], // Connection password.
            $shard_db_host['options'] // Connection options (from above).
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
        foreach ($this->shards as $_key => $_shard_db) {
            if ($shard_id >= $_shard_db['range']['from'] && $shard_id <= $_shard_db['range']['to']) {
                return $properties = $_shard_db['properties'];
            }
        } // unset($_key, $_shard_db); // Houskeeping.
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
