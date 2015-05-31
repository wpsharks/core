<?php
namespace WebSharks\Core\Classes;

use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Base abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsBase implements Interfaces\Constants
{
    use Traits\CacheMembers;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        $this->cacheInit();
    }
}
