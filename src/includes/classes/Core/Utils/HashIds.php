<?php
/**
 * Hash ID utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use Hashids\Hashids as Parser;

/**
 * Hash ID utilities.
 *
 * @since 150424 Initial release.
 */
class HashIds extends Classes\Core\Base\Core
{
    /**
     * Parser.
     *
     * @since 150424
     *
     * @type Parser
     */
    protected $Parser;

    /**
     * Class constructor.
     *
     * @since 150424 Adding hash IDs.
     * @since 17xxxx No constructor options.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!($key = $this->App->Config->©hash_ids['©hash_key'])) {
            throw $this->c::issue('Missing HashIds hash key.');
        }
        $this->Parser = new Parser($key, 4, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }

    /**
     * Encodes integers.
     *
     * @since 150424 Adding hash IDs.
     *
     * @param int ...$integers Variadic integers to encode.
     *
     * @return string A hash ID; e.g., `wQoQjgr`
     */
    public function encode(int ...$integers): string
    {
        return $this->Parser->encode(...$integers);
    }

    /**
     * Decodes a hash ID.
     *
     * @since 150424 Adding hash IDs.
     *
     * @param string $hash_id Hash ID.
     *
     * @return array Array of decoded IDs.
     */
    public function decode(string $hash_id): array
    {
        return $this->Parser->decode($hash_id);
    }

    /**
     * Decodes a hash ID.
     *
     * @since 161003 Single hash ID.
     *
     * @param string $hash_id Hash ID.
     *
     * @return int One decoded ID.
     */
    public function decodeOne(string $hash_id): int
    {
        $ids       = $this->Parser->decode($hash_id);
        return $id = (int) ($ids[0] ?? 0);
    }
}
