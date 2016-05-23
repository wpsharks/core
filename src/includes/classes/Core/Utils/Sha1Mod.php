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
 * SHA-1 Modulus.
 *
 * @since 150424 SHA-1 modulus.
 */
class Sha1Mod extends Classes\Core\Base\Core
{
    /**
     * Total MySQL DB shards.
     *
     * @since 160225
     *
     * @type int
     */
    protected $total_mysql_db_shards;

    /**
     * Class constructor.
     *
     * @since 150424 SHA-1 modulus.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->total_mysql_db_shards = count($this->App->Config->©mysql_db['©shards']);
    }

    /**
     * SHA-1 modulus.
     *
     * @since 150424 SHA-1 modulus.
     *
     * @param string $string  String or a SHA-1 hash.
     * @param int    $divisor Maximum allowable value.
     * @param bool   $is_sha1 String is already a SHA-1 hash?
     *
     * @return int SHA-1 modulus.
     */
    public function __invoke(string $string, int $divisor, bool $is_sha1 = false): int
    {
        if ($is_sha1) {
            $sha1 = $string;
        } else {
            $sha1 = sha1($string);
        }
        if (strlen($sha1) !== 40) {
            throw new Exception('SHA-1 hash not 40 chars.');
        }
        $sha1_first_15 = substr($sha1, 0, 15);
        $dividend      = hexdec($sha1_first_15);
        $divisor       = max(1, $divisor);
        return $dividend % $divisor;
    }

    /**
     * SHA-1 modulus shard ID.
     *
     * @since 150424 SHA-1 modulus.
     *
     * @param string $string       String or a SHA-1 hash.
     * @param bool   $is_sha1      String is already a SHA-1 hash?
     * @param int    $total_shards Total shards; defaults to `65536`.
     *
     * @return int SHA-1 modulus; i.e., a shard ID.
     *
     * @note Shard IDs are always zero-based, because a modulus will never be >= to the divisor.
     *  If `$total_shards is `65536`, min shard ID is `0`, max shard ID is `65535`.
     */
    public function shardId(string $string, bool $is_sha1 = false, int $total_shards = 65536): int
    {
        return $this->__invoke($string, $total_shards, $is_sha1);
    }

    /**
     * Assign shard ID.
     *
     * @since 150424 SHA-1 modulus.
     *
     * @param string $string  String or a SHA-1 hash.
     * @param bool   $is_sha1 String is already a SHA-1 hash?
     *
     * @return int Assigned shard ID; based on `sha1($string)`.
     *
     * @note This one is based on the actual number of configured shards;
     *  i.e., not on the upper limit of the shard ID itself. `65536` is not used here.
     */
    public function assignShardId(string $string, bool $is_sha1 = false)
    {
        if ($this->total_mysql_db_shards < 1) {
            throw new Exception('No MySQL DB shards available.');
        }
        return $this->shardId($string, $is_sha1, $this->total_mysql_db_shards);
    }
}
