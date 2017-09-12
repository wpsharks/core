<?php
/**
 * Core constructor.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
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
     * @var Classes\App
     */
    protected $App;

    /**
     * All facades.
     *
     * @since 160227
     *
     * @var \StdClass
     */
    protected $f;

    /**
     * Core facades.
     *
     * @since 160227
     *
     * @var string
     */
    protected $c;

    /**
     * Secondary core facades.
     *
     * @since 160227
     *
     * @var string
     */
    protected $s;

    /**
     * App facades.
     *
     * @since 160227
     *
     * @var string
     */
    protected $a;

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
        if (!($this->App = $App)) { // Must have!
            throw new Exception('Missing App instance.');
        }
        if (!isset($this->App->Facades)) {
            $this->App->Facades = (object) ['c' => null, 's' => null, 'a' => null];
        } // In case of the app itself; simply create references for now.
        // This also applies to other classes loaded early-on; e.g., Config, Di, Utils.

        $this->f = &$this->App->Facades;
        $this->c = &$this->App->Facades->c;
        $this->s = &$this->App->Facades->s;
        $this->a = &$this->App->Facades->a;
    }
}
