<?php
/**
 * Hash ID utilities.
 *
 * @author @jaswsinc
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
     * @var Parser
     */
    protected $Parser;

    /**
     * Class constructor.
     *
     * @since 150424 Adding hash IDs.
     *
     * @param Classes\App $App       Instance of App.
     * @param string      $salt      Secret key.
     * @param int         $min_chars Minumum chars.
     * @param string      $alphabet  Chars to use in ID.
     */
    public function __construct(Classes\App $App, string $key = '', int $min_chars = 0, string $alphabet = '')
    {
        parent::__construct($App);

        if (!$key && !($key = $this->App->Config->©hash_ids['©hash_key'])) {
            throw $this->c::issue('Missing HashIds hash key.');
        }
        $this->Parser = new Parser($key, $min_chars, $alphabet);
    }

    /**
     * Encodes integers.
     *
     * @since 150424 Adding hash IDs.
     *
     * @param int ...$integers Variadic integers to encode.
     *
     * @return string A hash ID; e.g., `xjFsdcl`
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
