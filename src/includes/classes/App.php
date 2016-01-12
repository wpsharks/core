<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
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
     * Namespace.
     *
     * @since 15xxxx
     *
     * @type string
     */
    public $namespace;

    /**
     * Namespace SHA-1.
     *
     * @since 15xxxx
     *
     * @type string
     */
    public $namespace_sha1;

    /**
     * Dir.
     *
     * @since 15xxxx
     *
     * @type string
     */
    public $dir;

    /**
     * Core dir.
     *
     * @since 15xxxx
     *
     * @type string
     */
    public $core_dir;

    /**
     * Core dir is vendor?
     *
     * @since 15xxxx
     *
     * @type bool
     */
    public $core_is_vendor;

    /**
     * Config.
     *
     * @since 15xxxx
     *
     * @type AppConfig
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
    const VERSION = '160112'; //v//

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $instance_base Instance base.
     * @param array $instance      Instance args (highest precedence).
     */
    public function __construct(array $instance_base = [], array $instance = [])
    {
        parent::__construct();

        $Class = new \ReflectionClass($this);

        $GLOBALS[self::class]       = $this;
        $GLOBALS[$Class->getName()] = $this;

        $this->namespace      = $Class->getNamespaceName();
        $this->namespace_sha1 = sha1($this->namespace);

        $this->dir = dirname($Class->getFileName(), 4);

        $this->core_dir       = dirname(__FILE__, 4);
        $this->core_is_vendor = mb_stripos($this->core_dir, '/vendor/') !== false;

        $this->Config = new AppConfig($instance_base, $instance);
        $this->Di     = new AppDi($this->Config->di['default_rule']);
        $this->Utils  = new AppUtils(); // Utility class access.

        $this->Di->addInstances([
            self::class => $this,
            $this, // Extender.
            $this->Config,
            $this->Utils,
        ]);
        $this->maybeDebug();
        $this->maybeSetLocales();
        $this->maybeHandleExceptions();
    }

    /**
     * Maybe setup debugging.
     *
     * @since 15xxxx Initial release.
     */
    protected function maybeDebug()
    {
        if ($this->Config->debug) {
            // All errors.
            error_reporting(E_ALL);

            // Display errors.
            ini_set('display_errors', 'yes');

            // Fail softly, because it can only go from `0` to `1`.
            // If the current value is `-1` this will trigger a warning.
            @ini_set('zend.assertions', '1');
        }
    }

    /**
     * Maybe handle exceptions.
     *
     * @since 15xxxx Initial release.
     */
    protected function maybeHandleExceptions()
    {
        if (!$this->Config->debug && $this->Config->handle_exceptions) {
            c\setup_exception_handler();
        }
    }

    /**
     * Maybe setup locales.
     *
     * @since 15xxxx Initial release.
     */
    protected function maybeSetLocales()
    {
        if ($this->Config->i18n['locales']) {

            // Try locale codes in a specific order.
            // See: <http://php.net/manual/en/function.setlocale.php>
            setlocale(LC_ALL, $this->Config->i18n['locales']);
        }
    }
}
