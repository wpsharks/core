<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Application.
 *
 * @since 150424 Initial release.
 */
class App extends Abs
{
    /**
     * Namespace.
     *
     * @since 150424
     *
     * @type string
     */
    public $namespace;

    /**
     * Namespace SHA-1.
     *
     * @since 150424
     *
     * @type string
     */
    public $namespace_sha1;

    /**
     * Dir.
     *
     * @since 150424
     *
     * @type string
     */
    public $dir;

    /**
     * Dir basename.
     *
     * @since 160223
     *
     * @type string
     */
    public $dir_basename;

    /**
     * Dir SHA-1.
     *
     * @since 150424
     *
     * @type string
     */
    public $dir_sha1;

    /**
     * Core dir.
     *
     * @since 150424
     *
     * @type string
     */
    public $core_dir;

    /**
     * Core dir basename.
     *
     * @since 160223
     *
     * @type string
     */
    public $core_dir_basename;

    /**
     * Core dir SHA-1.
     *
     * @since 160223
     *
     * @type string
     */
    public $core_dir_sha1;

    /**
     * Core dir is vendor?
     *
     * @since 150424
     *
     * @type bool
     */
    public $core_is_vendor;

    /**
     * Config.
     *
     * @since 150424
     *
     * @type AppConfig
     */
    public $Config;

    /**
     * Dicer.
     *
     * @since 150424
     *
     * @type AppDi
     */
    public $Di;

    /**
     * Utilities.
     *
     * @since 150424
     *
     * @type AppUtils
     */
    public $Utils;

    /**
     * Version.
     *
     * @since 150424
     *
     * @type string Version.
     */
    const VERSION = '160124'; //v//

    /**
     * Constructor.
     *
     * @since 150424 Initial release.
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

        $this->dir          = dirname($Class->getFileName(), 4);
        $this->dir_basename = basename($this->dir);
        $this->dir_sha1     = sha1($this->dir);

        $this->core_dir          = dirname(__FILE__, 4);
        $this->core_dir_basename = basename($this->core_dir);
        $this->core_dir_sha1     = sha1($this->core_dir);
        $this->core_is_vendor    = mb_stripos($this->core_dir, '/vendor/') !== false;

        $this->Config = new AppConfig($this, $instance_base, $instance);
        $this->Di     = new AppDi($this, $this->Config->di['default_rule']);
        $this->Utils  = new AppUtils($this); // Utility class access.

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
     * @since 150424 Initial release.
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
     * @since 150424 Initial release.
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
     * @since 150424 Initial release.
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
