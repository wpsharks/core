<?php
/**
 * UUID 64 parser/builder.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
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
 * UUID 64 parser/builder.
 *
 * @since 150424 Initial release.
 */
class Uuid64 extends Classes\Core\Base\Core
{
    /**
     * UUID64 validator.
     *
     * @since 150424 Initial release.
     *
     * @param int      $uuid              Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param int|null $expecting_type_id Expected type ID.
     *
     * @return int Validated UUID64 (64-bits signed max `9223372036854775807`).
     */
    public function validate(int $uuid, int $expecting_type_id = null): int
    {
        if ($uuid < 1 || $uuid > 9223372036854775807) {
            throw $this->c::issue('UUID64 out of range.');
        }
        if (isset($expecting_type_id)) {
            $this->typeIdIn($uuid, $expecting_type_id);
        }
        return $uuid;
    }

    /**
     * Shard ID from UUID64.
     *
     * @since 150424 Initial release.
     *
     * @param int  $uuid     Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param bool $validate Validate the shard ID after extraction? Defaults to `true`.
     *
     * @return int Shard ID from UUID64 (16 bits unsigned max `65535`).
     */
    public function shardIdIn(int $uuid, bool $validate = true): int
    {
        $shard_id = ($uuid >> (64 - 2 - 16)) & 65535;

        if ($validate) {
            $this->validateShardId($shard_id);
        }
        return $shard_id;
    }

    /**
     * Validate shard ID.
     *
     * @since 150424 Initial release.
     *
     * @param int $shard_id Input shard ID (16 bits unsigned max `65535`).
     *
     * @return int Validated shard ID (16 bits unsigned max `65535`).
     */
    public function validateShardId(int $shard_id): int
    {
        if ($shard_id < 0 || $shard_id > 65535) {
            throw $this->c::issue('Shard ID out of range.');
        }
        return $shard_id;
    }

    /**
     * Type ID from UUID64.
     *
     * @since 150424 Initial release.
     *
     * @param int      $uuid              Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param int|null $expecting_type_id Expected type ID; i.e., validate for this type ID?
     * @param bool     $validate          Validate the type ID after extraction? Defaults to `true`.
     *
     * @return int Type ID from UUID64 (8 bits unsigned max `255`).
     */
    public function typeIdIn(int $uuid, int $expecting_type_id = null, bool $validate = true): int
    {
        $type_id = ($uuid >> (64 - 2 - 16 - 8)) & 255;

        if (isset($expecting_type_id)) {
            $this->validateTypeId($type_id, $expecting_type_id);
        } elseif ($validate) {
            $this->validateTypeId($type_id);
        }
        return $type_id;
    }

    /**
     * Validate type ID.
     *
     * @since 150424 Initial release.
     *
     * @param int      $type_id           Input type ID (8 bits unsigned max `255`).
     * @param int|null $expecting_type_id Expected type ID.
     *
     * @return int Validated type ID (8 bits unsigned max `255`).
     */
    public function validateTypeId(int $type_id, int $expecting_type_id = null): int
    {
        if ($type_id < 0 || $type_id > 255) {
            throw $this->c::issue('Type ID out of range.');
        } elseif (isset($expecting_type_id) && $type_id !== $expecting_type_id) {
            throw $this->c::issue(sprintf('Type ID mismatch: `%1$s`/`%2$s`.', $type_id, $expecting_type_id));
        }
        return $type_id;
    }

    /**
     * Local ID from UUID64.
     *
     * @since 150424 Initial release.
     *
     * @param int  $uuid     Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param bool $validate Validate the local ID after extraction? Defaults to `true`.
     *
     * @return int Local ID from UUID64 (38 bits unsigned max `274877906943`).
     */
    public function localIdIn(int $uuid, bool $validate = true): int
    {
        $local_id = ($uuid >> (64 - 2 - 16 - 8 - 38)) & 274877906943;

        if ($validate) {
            $this->validateLocalId($local_id);
        }
        return $local_id;
    }

    /**
     * Validate local ID.
     *
     * @since 150424 Initial release.
     *
     * @param int $local_id Input local ID (38 bits unsigned max `274877906943`).
     *
     * @return int Validated local ID (38 bits unsigned max `274877906943`).
     */
    public function validateLocalId(int $local_id): int
    {
        if ($local_id < 1 || $local_id > 274877906943) {
            throw $this->c::issue('Local ID out of range.');
        }
        return $local_id;
    }

    /**
     * UUID64 parser.
     *
     * @since 150424 Initial release.
     *
     * @param int      $uuid              Input UUID64 (64 bits signed max `9223372036854775807`).
     * @param int|null $expecting_type_id Expected type ID; i.e., validate this type ID?
     * @param bool     $validate          Validate the UUID and each part?
     *
     * @return array IDs: `shard_id`, `type_id`, `local_id` from UUID64.
     *
     *               - `shard_id` (16 bits unsigned max `65535`).
     *               - `type_id` (8 bits unsigned max `255`).
     *               - `local_id` (38 bits unsigned max `274877906943`).
     */
    public function parse(int $uuid, int $expecting_type_id = null, bool $validate = true): array
    {
        if ($validate) {
            $this->validate($uuid);
        }
        $shard_id = $this->shardIdIn($uuid, $validate);
        $type_id  = $this->typeIdIn($uuid, $expecting_type_id, $validate);
        $local_id = $this->localIdIn($uuid, $validate);

        return compact('shard_id', 'type_id', 'local_id');
    }

    /**
     * UUID64 builder.
     *
     * @since 150424 Initial release.
     *
     * @param int  $shard_id Shard ID (16 bits unsigned max `65535`).
     * @param int  $type_id  Type ID (8 bits unsigned max `255`).
     * @param int  $local_id Local ID (38 bits unsigned max `274877906943`).
     * @param bool $validate Validate each of the IDs first? Defaults to `true`.
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
    public function build(int $shard_id, int $type_id, int $local_id, bool $validate = true): int
    {
        if ($validate) {
            $this->validateShardId($shard_id);
            $this->validateTypeId($type_id);
            $this->validateLocalId($local_id);
        }
        return (0 << (64 - 2))
            | ($shard_id << (64 - 2 - 16))
            | ($type_id << (64 - 2 - 16 - 8))
            | ($local_id << (64 - 2 - 16 - 8 - 38));
    }
}
