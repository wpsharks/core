<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Application.
 *
 * @since 15xxxx Initial release.
 */
class App extends AbsCore
{
    /**
     * Config.
     *
     * @since 15xxxx
     *
     * @type Config
     */
    public $Config;

    /**
     * Dicer.
     *
     * @since 15xxxx
     *
     * @type AppDi
     */
    public $Di;

    /**
     * Utilities.
     *
     * @since 15xxxx
     *
     * @type AppUtils
     */
    public $Utils;

    /**
     * Version.
     *
     * @since 15xxxx
     *
     * @type string Version.
     */
    const VERSION = '151126'; //v//

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $instance Instance args.
     */
    public function __construct(array $instance = [])
    {
        parent::__construct();

        $this->Config = new AppConfig($instance);

        $this->Di = new AppDi($this->Config->di_default_rule);
        $this->Di->addInstances([self::class => $this, $this]);

        $this->Utils = $this->Di->get(AppUtils::class);
    }
}
