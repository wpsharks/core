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
     * UUID64 validator.
     *
     * @since 15xxxx Initial release.
     *
     * @param int      $uuid              Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param int|null $expecting_type_id Expected type ID.
     *
     * @return int Validated UUID64 (64-bits signed max `9223372036854775807`).
     */
    public function validate(int $uuid, int $expecting_type_id = null): int
    {
        if ($uuid < 1 || $uuid > 9223372036854775807) {
            throw new \Exception('UUID64 out of range.');
        }
        if (isset($expecting_type_id)) {
            $this->typeIdIn($uuid, $expecting_type_id);
        }
        return $uuid;
    }

    /**
     * Shard ID from UUID64.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $uuid Input UUID64 (64 bits signed max `9223372036854775807`).
     *
     * @return int Shard ID from UUID64 (16 bits unsigned max `65535`).
     */
    public function shardIdIn(int $uuid): int
    {
        return $this->validateShardId(($uuid >> (64 - 2 - 16)) & 65535);
    }

    /**
     * Validate shard ID.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $shard_id Input shard ID (16 bits unsigned max `65535`).
     *
     * @return int Validated shard ID (16 bits unsigned max `65535`).
     */
    public function validateShardId(int $shard_id): int
    {
        if ($shard_id < 0 || $shard_id > 65535) {
            throw new \Exception('Shard ID out of range.');
        }
        return $shard_id;
    }

    /**
     * Type ID from UUID64.
     *
     * @since 15xxxx Initial release.
     *
     * @param int      $uuid              Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param int|null $expecting_type_id Expected type ID.
     *
     * @return int Type ID from UUID64 (8 bits unsigned max `255`).
     */
    public function typeIdIn(int $uuid, int $expecting_type_id = null): int
    {
        $type_id = $this->validateTypeId(($uuid >> (64 - 2 - 16 - 8)) & 255);

        if (isset($expecting_type_id)) {
            $this->validateTypeId($type_id, $expecting_type_id);
        }
        return $type_id;
    }

    /**
     * Validate type ID.
     *
     * @since 15xxxx Initial release.
     *
     * @param int      $type_id           Input type ID (8 bits unsigned max `255`).
     * @param int|null $expecting_type_id Expected type ID.
     *
     * @return int Validated type ID (8 bits unsigned max `255`).
     */
    public function validateTypeId(int $type_id, int $expecting_type_id = null): int
    {
        if ($type_id < 0 || $type_id > 255) {
            throw new \Exception('Type ID out of range.');
        } elseif (isset($expecting_type_id) && $type_id !== $expecting_type_id) {
            throw new \Exception(sprintf('Type ID mismatch: `%1$s`/`%2$s`.', $type_id, $expecting_type_id));
        }
        return $type_id;
    }

    /**
     * Local ID from UUID64.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $uuid Input UUID64 (64 bits signed max `9223372036854775807`).
     *
     * @return int Local ID from UUID64 (38 bits unsigned max `274877906943`).
     */
    public function localIdIn(int $uuid): int
    {
        return $this->validateLocalId(($uuid >> (64 - 2 - 16 - 8 - 38)) & 274877906943);
    }

    /**
     * Validate local ID.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $local_id Input local ID (38 bits unsigned max `274877906943`).
     *
     * @return int Validated local ID (38 bits unsigned max `274877906943`).
     */
    public function validateLocalId(int $local_id): int
    {
        if ($local_id < 1 || $local_id > 274877906943) {
            throw new \Exception('Local ID out of range.');
        }
        return $local_id;
    }

    /**
     * UUID64 parser.
     *
     * @since 15xxxx Initial release.
     *
     * @param int      $uuid              Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param int|null $expecting_type_id Expected type ID.
     *
     * @return array IDs: `shard_id`, `type_id`, `local_id` from UUID64.
     *
     *               - `shard_id` (16 bits unsigned max `65535`).
     *               - `type_id` (8 bits unsigned max `255`).
     *               - `local_id` (38 bits unsigned max `274877906943`).
     */
    public function parse(int $uuid, int $expecting_type_id = null): array
    {
        $this->validate($uuid);

        $shard_id = $this->shardIdIn($uuid);
        $type_id  = $this->typeIdIn($uuid);
        $local_id = $this->localIdIn($uuid);

        if (isset($expecting_type_id)) {
            $this->validateTypeId($type_id, $expecting_type_id);
        }
        return compact('shard_id', 'type_id', 'local_id');
    }

    /**
     * UUID64 builder.
     *
     * @since 15xxxx Initial release.
     *
     * @param int $shard_id Shard ID (16 bits unsigned max `65535`).
     * @param int $type_id  Type ID (8 bits unsigned max `255`).
     * @param int $local_id Local ID (38 bits unsigned max `274877906943`).
     *
     * @return int UUID64 (64 bits signed max `9223372036854775807`).
     *
     *             - (2 reserve bits; set to a value of 0).
     *             - `shard_id` (16 bits unsigned max `65535`).
     *             - `type_id` (8 bits unsigned max `255`).
     *             - `local_id` (38 bits unsigned max `274877906943`).
     *
     *             = 64 bits signed max: `9223372036854775807` = `PHP_INT_MAX`.
     */
    public function build(int $shard_id, int $type_id, int $local_id): int
    {
        $shard_id = $this->validateShardId($shard_id);
        $type_id  = $this->validateTypeId($type_id);
        $local_id = $this->validateLocalId($local_id);

        return (0 << (64 - 2))
            | ($shard_id << (64 - 2 - 16))
            | ($type_id << (64 - 2 - 16 - 8))
            | ($local_id << (64 - 2 - 16 - 8 - 38));
    }
}
