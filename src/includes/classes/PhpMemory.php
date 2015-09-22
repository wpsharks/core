<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * PHP memory utilities.
 *
 * @since 150424 Initial release.
 */
class PhpMemory extends AbsBase
{
    protected $FsSize;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        FsSize $FsSize
    ) {
        parent::__construct();

        $this->FsSize = $FsSize;
    }

    /**
     * Get/set available memory.
     *
     * @since 150424 Initial release.
     *
     * @param string|null $limit Size abbr.
     *
     * @return float Current max memory; in bytes.
     */
    public function limit(string $limit = null): float
    {
        if (isset($limit) && $limit) {
            @ini_set('memory_limit', $limit);
        }
        return $this->FsSize->abbrBytes(ini_get('memory_limit'));
    }
}
