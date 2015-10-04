<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * UUID 64 parser/builder.
 *
 * @since 150424 Initial release.
 */
class Uuid64 extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Shard ID from UUID64.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $uuid Input UUID64.
     *
     * @return int Shard ID from UUID64.
     */
    public function shardIdIn(int $uuid): int
    {
        return ($uuid >> 46) & 65535;
    }

    /**
     * Type ID from UUID64.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $uuid Input UUID64.
     *
     * @return int Type ID from UUID64.
     */
    public function typeIdIn(int $uuid): int
    {
        return ($uuid >> 38) & 255;
    }

    /**
     * Local ID from UUID64.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $uuid Input UUID64.
     *
     * @return int Local ID from UUID64.
     */
    public function localIdIn(int $uuid): int
    {
        return ($uuid >> 0) & 274877906943;
    }

    /**
     * UUID64 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $shard_id Shard ID.
     * @param int $type_id  Type ID.
     * @param int $local_id Local ID.
     *
     * @return int UUID64 (64 bits).
     */
    public function build(int $shard_id, int $type_id, int $local_id): int
    {
        if ($shard_id > 65535) {
            throw new \Exception('Shard ID out of range.');
        }
        if ($type_id > 255) {
            throw new \Exception('Type ID out of range.');
        }
        if ($local_id > 274877906943) {
            throw new \Exception('Local ID out of range.');
        }
        return (0 << 62) | ($shard_id << 46) | ($type_id << 38) | ($local_id << 0);
    }
}
