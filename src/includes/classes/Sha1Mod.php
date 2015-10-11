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
     * SHA-1 [sha1, modulus].
     *
     * @since 15xxxx SHA-1 modulus.
     *
     * @param string $string  String.
     * @param int    $modulus The modulus.
     *
     * @return array SHA-1 [sha1, modulus].
     */
    public function __invoke(string $string, int $modulus = 65535): array
    {
        $sha1          = sha1($string);
        $sha1_first_15 = substr($sha1, 0, 15);
        $modulus       = hexdec($sha1_first_15) % $modulus;

        return compact('sha1', 'modulus');
    }

    /**
     * SHA-1 modulus shard ID.
     *
     * @since 15xxxx SHA-1 modulus.
     *
     * @param string $sha1 SHA-1 hash.
     *
     * @return int SHA-1 modulus shard ID.
     */
    public function shardId(string $sha1)
    {
        $sha1_first_15 = substr($sha1, 0, 15);
        $modulus       = hexdec($sha1_first_15) % 65535;

        return $modulus;
    }
}
