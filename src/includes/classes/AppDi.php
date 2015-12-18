<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App DI.
 *
 * @since 15xxxx Initial release.
 */
class AppDi extends \WebSharks\Dicer\Di
{
    /**
     * App.
     *
     * @since 15xxxx
     *
     * @type App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(array $global_default_rule = [])
    {
        parent::__construct($global_default_rule);

        $this->App = $GLOBALS[App::class];
    }
}
