<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Base abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsBase extends AbsCore
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
     * Utilities.
     *
     * @since 15xxxx
     *
     * @type AppUtils
     */
    protected $Utils;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(App $App)
    {
        parent::__construct();

        $this->App   = $App;
        $this->Utils = $App->Utils;
    }
}
