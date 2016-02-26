<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

/**
 * Core constructor.
 *
 * @since 160223 Initial release.
 */
trait CoreConstructor
{
    /**
     * App.
     *
     * @since 160223
     *
     * @type Classes\App
     */
    protected $App;

    /**
     * Facades.
     *
     * @since 160223
     *
     * @type string
     */
    protected $c; #{AppFacades}

    /**
     * Class constructor.
     *
     * @since 160223 Initial release.
     *
     * @param Classes\App|null $App Instance of App.
     */
    public function __construct(Classes\App $App = null)
    {
        if (!$App && $this instanceof Classes\App) {
            $App = $this; // Self reference.
        }
        if (!$App) { // Must have an App instance!
            throw new Exception('Missing App instance.');
        }
        $this->App = $App;
        $this->c   = &$this->App->Facades;
    }
}
