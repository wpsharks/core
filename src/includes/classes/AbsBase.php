<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;
use WebSharks\Core\Interfaces;

/**
 * Base abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsBase implements Interfaces\Constants, \Serializable, \JsonSerializable
{
    use Traits\CacheMembers;
    use Traits\OverloadMembers;

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
