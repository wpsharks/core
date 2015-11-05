<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * SHA-1 Modulus.
 *
 * @since 15xxxx SHA-1 modulus.
 */
class Sha1Mod extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx SHA-1 modulus.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * SHA-1 modulus.
     *
     * @since 15xxxx SHA-1 modulus.
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
     * @since 15xxxx SHA-1 modulus.
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
}
