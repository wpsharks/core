<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * PDO utilities.
 *
 * @since 150424 Initial release.
 */
class Pdo extends Classes\Core\Base\Core
{
    /**
     * Current PDO.
     *
     * @since 150424
     *
     * @type \PDO|null
     */
    public $current;

    /**
     * Constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);
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
        if (!($Pdo = $this->current)) {
            throw new Exception('No PDO yet; i.e., unable to quote.');
        }
        switch (gettype($value)) {
            case 'int':
            case 'integer':
                return $Pdo->quote((string) $value, $Pdo::PARAM_INT);

            case 'float':
            case 'double':
                return $Pdo->quote((string) $value, $Pdo::PARAM_STR);

            case 'str':
            case 'string':
                return $Pdo->quote($value, $Pdo::PARAM_STR);

            default: // Unexpected data type.
                throw new Exception('Unexpected data type; unable to quote.');
        }
    }

    /**
     * PDO instance for a DB.
     *
     * @since 150424 Initial release.
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
                $shard_id = $this->c::uuid64ShardIdIn($uuid);
            } else {
                $shard_id = 0; // Default.
            }
        } // Now we acquire the configuration.

        $shard_db = $this->shardDbConfig($shard_id);

        if (empty($this->App->Config->©mysql_db['©hosts'][$shard_db['©host']])) {
            throw new Exception(sprintf('Missing host for shard ID: `%1$s`.', $shard_id));
        }
        $shard_db_host = $this->App->Config->©mysql_db['©hosts'][$shard_db['©host']];

        // Check the cache. Already connected to this DB host?

        if (($Pdo = &$this->cacheKey(__FUNCTION__, $shard_db['©host']))) {
            $Pdo->exec('use `'.$shard_db['©name'].'`');
            $this->current = $Pdo;
            return $Pdo;
        }
        // Otherwise, we need to establish a new connection.

        $shard_db_host_options = [
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
        if ($shard_db_host['©charset']) { // Use a specific charset?
            $shard_db_host_options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES \''.$shard_db_host['©charset'].'\'';

            if ($shard_db_host['©collate']) { // Also a specific collation?
                $shard_db_host_options[\PDO::MYSQL_ATTR_INIT_COMMAND] .= ' COLLATE \''.$shard_db_host['©collate'].'\'';
            }
        }
        if ($shard_db_host['©ssl_enable'] && $shard_db_host['©ssl_key'] && $shard_db_host['©ssl_crt'] && $shard_db_host['©ssl_ca'] && $shard_db_host['©ssl_cipher']) {
            $shard_db_host_options[\PDO::MYSQL_ATTR_SSL_KEY]    = $shard_db_host['©ssl_key'];
            $shard_db_host_options[\PDO::MYSQL_ATTR_SSL_CERT]   = $shard_db_host['©ssl_crt'];
            $shard_db_host_options[\PDO::MYSQL_ATTR_SSL_CA]     = $shard_db_host['©ssl_ca'];
            $shard_db_host_options[\PDO::MYSQL_ATTR_SSL_CIPHER] = $shard_db_host['©ssl_cipher'];
        }
        $Pdo = new \PDO(// By reference. We are caching this connection.
            'mysql:host='.$shard_db['©host'].';port='.$shard_db_host['©port'],
            $shard_db_host['©username'], // Connection username.
            $shard_db_host['©password'], // Connection password.
            $shard_db_host_options // Connection options (from above).
        );
        $Pdo->exec('use `'.$shard_db['©name'].'`');
        $this->current = $Pdo;
        return $Pdo;
    }

    /**
     * Shard DB config properties.
     *
     * @since 150424 Initial release.
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
        foreach ($this->App->Config->©mysql_db['©shards'] as $_key => $_shard_db) {
            if ($shard_id >= $_shard_db['©range']['©from'] && $shard_id <= $_shard_db['©range']['©to']) {
                return $properties = $_shard_db['©properties'];
            }
        } // unset($_key, $_shard_db); // Houskeeping.
        throw new Exception(sprintf('Missing DB info for shard ID: `%1$s`.', $shard_id));
    }
}
