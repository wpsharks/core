<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Dicer\Di;

/**
 * App DI.
 *
 * @since 150424 Initial release.
 */
class AppDi extends Di
{
    /**
     * App.
     *
     * @since 150424
     *
     * @type App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param App   $App                 Instance of App.
     * @param array $global_default_rule Default rule.
     */
    public function __construct(App $App, array $global_default_rule = [])
    {
        parent::__construct($global_default_rule);

        $this->App = $App;
    }
}
