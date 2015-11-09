<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;

/**
 * Base abstraction w/ overload members.
 *
 * @since 15xxxx Adding an additional base class.
 */
abstract class AbsOverloadBase extends AbsBase
{
    use Traits\OverloadMembers;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
        $this->overloadInit();
    }
}
