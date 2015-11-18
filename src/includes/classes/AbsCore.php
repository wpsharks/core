<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;
use WebSharks\Core\Interfaces;

/**
 * Core abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsCore implements Interfaces\Constants, \Serializable, \JsonSerializable
{
    use Traits\CacheMembers;
    use Traits\OverloadMembers;
    use Traits\TranslationMembers;

    /**
     * Version.
     *
     * @since 15xxxx
     *
     * @type string Version.
     */
    const VERSION = '151118'; //v//

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
