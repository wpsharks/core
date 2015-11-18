<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Base abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class AbsBase extends AbsCore
{
    /**
     * Dicer.
     *
     * @since 15xxxx
     *
     * @type AppDi
     */
    protected $Di;

    /**
     * App.
     *
     * @since 15xxxx
     *
     * @type AbsApp
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
    public function __construct(AbsApp $App)
    {
        parent::__construct();

        $this->App   = $App;
        $this->Di    = $App->Di;
        $this->Utils = $App->Utils;
    }
}
