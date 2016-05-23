<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

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
     * All facades.
     *
     * @since 160227
     *
     * @type array
     */
    protected $f;

    /**
     * Core facades.
     *
     * @since 160227
     *
     * @type string
     */
    protected $c;

    /**
     * App facades.
     *
     * @since 160227
     *
     * @type string
     */
    protected $a;

    /**
     * Secondary core facades.
     *
     * @since 160227
     *
     * @type string
     */
    protected $s;

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
        $this->f   = &$this->App->facades;
        $this->c   = &$this->App->facades['c'];
        $this->a   = &$this->App->facades['a'];
        $this->s   = &$this->App->facades['s'];
    }
}
