<?php
namespace WebSharks\Core\Classes;

use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Base Abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsBase implements Interfaces\Constants
{
    use Traits\CacheUtils;
    use Traits\Definitions;

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
