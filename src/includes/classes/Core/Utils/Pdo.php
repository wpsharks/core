<?php
/**
 * PDO utilities.
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

        if (!class_exists('PDO')) {
            throw $this->c::issue('Missing PDO extension for PHP.');
        }
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
            throw $this->c::issue('No PDO yet; i.e., unable to quote.');
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
                throw $this->c::issue('Unexpected data type; unable to quote.');
        }
    }

    /**
     * PDO instance for a DB.
     *
     * @since 150424 Initial release.
     * @since 17xxxx Additional arg/types.
     *
     * @param int|string|null $shard_id_or_host     Shard ID or DB host.
     * @param int|string|null $uuid_or_host_db_name UUID64 or host DB name.
     *
     * @return \PDO A PDO class instance.
     */
    public function get($shard_id_or_host = null, $uuid_or_host_db_name = null): \PDO
    {
        // Acquire DB configuration details.

        $db = $this->dbConfig($shard_id_or_host, $uuid_or_host_db_name);

        // Are we already connected to this host?

        if (($Pdo = &$this->cacheKey(__FUNCTION__, $db['©host']))) {
            if (empty($Pdo->db_name) || $Pdo->db_name !== $db['©name']) {
                $Pdo->exec('use `'.$db['©name'].'`');
                $Pdo->db_name = $db['©name'];
            }
            return $this->current = $Pdo;
        }
        // Otherwise, we need to establish a new connection.

        if (empty($this->App->Config->©mysql_db['©hosts'][$db['©host']])) {
            throw $this->c::issue(sprintf('Missing DB host: `%1$s`.', $db['©host']));
        }
        $db_host         = $this->App->Config->©mysql_db['©hosts'][$db['©host']];
        $db_host_options = [
            \PDO::ATTR_TIMEOUT => 5,

            \PDO::ATTR_AUTOCOMMIT       => true,
            \PDO::ATTR_EMULATE_PREPARES => false,

            \PDO::ATTR_CASE               => \PDO::CASE_NATURAL,
            \PDO::ATTR_ORACLE_NULLS       => \PDO::NULL_NATURAL,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,

            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            \PDO::ATTR_ERRMODE                  => \PDO::ERRMODE_EXCEPTION,
        ];
        if ($db_host['©charset']) { // Use a specific charset?
            $db_host_options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES \''.$db_host['©charset'].'\'';

            if ($db_host['©collate']) { // Also a specific collation?
                $db_host_options[\PDO::MYSQL_ATTR_INIT_COMMAND] .= ' COLLATE \''.$db_host['©collate'].'\'';
            }
        }
        if ($db_host['©ssl_enable'] && $db_host['©ssl_key'] && $db_host['©ssl_crt'] && $db_host['©ssl_ca'] && $db_host['©ssl_cipher']) {
            $db_host_options[\PDO::MYSQL_ATTR_SSL_KEY]    = $db_host['©ssl_key'];
            $db_host_options[\PDO::MYSQL_ATTR_SSL_CERT]   = $db_host['©ssl_crt'];
            $db_host_options[\PDO::MYSQL_ATTR_SSL_CA]     = $db_host['©ssl_ca'];
            $db_host_options[\PDO::MYSQL_ATTR_SSL_CIPHER] = $db_host['©ssl_cipher'];
        }
        $Pdo = new \PDO('mysql:host='.$db['©host'].';port='.$db_host['©port'], $db_host['©username'], $db_host['©password'], $db_host_options);

        $Pdo->exec('use `'.$db['©name'].'`');
        $Pdo->db_name         = $db['©name'];
        return $this->current = $Pdo;
    }

    /**
     * DB config properties.
     *
     * @since 150424 Initial release.
     * @since 17xxxx Additional arg/types.
     *
     * @param int|string|null $shard_id_or_host     Shard ID or DB host.
     * @param int|string|null $uuid_or_host_db_name UUID64 or host DB name.
     *
     * @return array DB config properties.
     */
    protected function dbConfig($shard_id_or_host = null, $uuid_or_host_db_name = null): array
    {
        $cache_keys = [$shard_id_or_host, $uuid_or_host_db_name];

        if (($properties = &$this->cacheKey(__FUNCTION__, $cache_keys)) !== null) {
            return $properties; // Cached this already.
        }
        if (!isset($shard_id_or_host)) {
            $shard_id_or_host = is_int($uuid_or_host_db_name)
                ? $this->c::uuid64ShardIdIn($uuid_or_host_db_name)
                : $this->App->Config->©mysql_db['©default']['©host'];
        }
        if (!isset($uuid_or_host_db_name) && is_string($shard_id_or_host)) {
            $uuid_or_host_db_name = $this->App->Config->©mysql_db['©default']['©name'];
        }
        if (is_int($shard_id = $shard_id_or_host) && $shard_id >= 0) {
            foreach ($this->App->Config->©mysql_db['©shards'] as $_key => $_shard_db) {
                if ($shard_id >= $_shard_db['©range']['©from'] && $shard_id <= $_shard_db['©range']['©to']) {
                    return $properties = $_shard_db['©properties'];
                }
            } // unset($_key, $_shard_db);
        } elseif (is_string($host = $shard_id_or_host) && is_string($host_db_name) && $host && $host_db_name) {
            return $properties = ['©host' => $host, '©name' => $host_db_name];
        }
        throw $this->c::issue(sprintf('Missing DB config for: `%1$s`, `%2$s`.', $shard_id_or_host, $uuid_or_host_db_name));
    }
}
