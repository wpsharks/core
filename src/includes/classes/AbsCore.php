<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\AppUtils;
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
    use Traits\OverloadMembers;

    use Traits\QuickEscMembers;
    use Traits\QuickI18nMembers;

    /**
     * Core dir.
     *
     * @since 15xxxx
     *
     * @type string
     */
    protected $¤core_dir;

    /**
     * Core dir is vendor?
     *
     * @since 15xxxx
     *
     * @type bool
     */
    protected $¤core_dir_is_vendor;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        $this->¤core_dir           = dirname(__FILE__, 4);
        $this->¤core_dir_is_vendor = mb_strpos($this->¤core_dir, '/vendor/') !== false;
    }
}
