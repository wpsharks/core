<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Hash IDs.
 *
 * @since 150424 Initial release.
 */
class HashIds extends AbsBase
{
    protected $HashIds;

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
        string $key,
        int $min_chars = 0,
        string $alphabet = ''
    ) {
        parent::__construct();

        $this->HashIds = new \Hashids\Hashids($key, $min_chars, $alphabet);
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
        return $this->HashIds->encode(...$integers);
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
        return $this->HashIds->decode($hash_id);
    }
}
