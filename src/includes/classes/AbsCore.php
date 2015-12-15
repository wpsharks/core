<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Core abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsCore implements \Serializable, \JsonSerializable
{
    use Traits\CacheMembers;
    use Traits\I18nMembers;
    use Traits\OverloadMembers;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
    }
}
