<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use Hashids\Hashids as Parser;

/**
 * Hash IDs.
 *
 * @since 150424 Initial release.
 */
class HashIds extends Classes\AppBase
{
    /**
     * Parser.
     *
     * @since 15xxxx
     *
     * @type Parser
     */
    protected $Parser;

    /**
     * Class constructor.
     *
     * @since 15xxxx Adding hash IDs.
     *
     * @param string $salt      Secret key.
     * @param int    $min_chars Minumum chars.
     * @param string $alphabet  Chars to use in ID.
     */
    public function __construct(
        string $key = '',
        int $min_chars = 0,
        string $alphabet = ''
    ) {
        parent::__construct();

        if (!$key && !($key = $this->App->Config->hash_ids['hash_key'])) {
            throw new Exception('Missing HashIds hash key.');
        }
        $this->Parser = new Parser($key, $min_chars, $alphabet);
    }

    /**
     * Encodes integers.
     *
     * @since 15xxxx Adding hash IDs.
     *
     * @param int ...$integers Integers to encode.
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
     * @since 15xxxx Adding hash IDs.
     *
     * @param string $hash_id Hash ID.
     *
     * @return array An array of integers.
     */
    public function decode(string $hash_id): array
    {
        return $this->Parser->decode($hash_id);
    }
}
