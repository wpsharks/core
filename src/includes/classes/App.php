<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Application.
 *
 * @since 150424 Initial release.
 */
class App extends Classes\Core\Base\Core
{
    /**
     * Parent app.
     *
     * @since 160224
     *
     * @type Classes\App|null
     */
    public $parent;

    /**
     * Class.
     *
     * @since 160223
     *
     * @type string
     */
    public $class;

    /**
     * Class SHA-1.
     *
     * @since 160223
     *
     * @type string
     */
    public $class_sha1;

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
     * File.
     *
     * @since 160225
     *
     * @type string
     */
    public $file;

    /**
     * File basename.
     *
     * @since 160227
     *
     * @type string
     */
    public $file_basename;

    /**
     * File SHA-1.
     *
     * @since 160225
     *
     * @type string
     */
    public $file_sha1;

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
     * Base dir.
     *
     * @since 160227
     *
     * @type string
     */
    public $base_dir;

    /**
     * Base dir basename.
     *
     * @since 160227
     *
     * @type string
     */
    public $base_dir_basename;

    /**
     * Base dir SHA-1.
     *
     * @since 160227
     *
     * @type string
     */
    public $base_dir_sha1;

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
     * @type Classes\Core\Base\Config
     */
    public $Config;

    /**
     * Dicer.
     *
     * @since 150424
     *
     * @type Classes\Core\Base\Di
     */
    public $Di;

    /**
     * Utilities.
     *
     * @since 150424
     *
     * @type Classes\Core\Base\Utils
     */
    public $Utils;

    /**
     * Facades.
     *
     * @since 160227
     *
     * @type array
     */
    public $facades = [
        'c' => '',
        'a' => '',
        's' => '',
    ];

    /**
     * Version.
     *
     * @since 150424
     *
     * @type string Version.
     */
    const VERSION = '160226'; //v//

    /**
     * Constructor.
     *
     * @since 150424 Initial release.
     *
     * @param array            $instance_base Instance base.
     * @param array            $instance      Instance args (highest precedence).
     * @param Classes\App|null $parent        Parent app (optional).
     * @param array            $args          Any additional behavioral args.
     */
    public function __construct(array $instance_base = [], array $instance = [], Classes\App $parent = null, array $args = [])
    {
        parent::__construct();

        $this->parent = $parent;

        $Class = new \ReflectionClass($this);

        $this->class      = $Class->getName();
        $this->class_sha1 = sha1($this->class);

        $this->namespace      = $Class->getNamespaceName();
        $this->namespace_sha1 = sha1($this->namespace);

        $this->file          = $Class->getFileName();
        $this->file_basename = basename($this->file, '.php');
        $this->file_sha1     = sha1($this->file);

        $this->dir          = dirname($this->file);
        $this->dir_basename = basename($this->dir);
        $this->dir_sha1     = sha1($this->dir);

        $this->base_dir          = dirname($this->dir, 3);
        $this->base_dir_basename = basename($this->base_dir);
        $this->base_dir_sha1     = sha1($this->base_dir);

        $this->core_dir          = dirname(__FILE__, 4);
        $this->core_dir_basename = basename($this->core_dir);
        $this->core_dir_sha1     = sha1($this->core_dir);
        $this->core_is_vendor    = mb_stripos($this->core_dir, '/vendor/') !== false;

        if (isset($GLOBALS[$this->class])) {
            throw new Exception(sprintf('One instance of `%1$s` only please.', $this->class));
        }
        if (mb_substr($this->namespace, -8) !== '\\Classes') {
            throw new Exception(sprintf('Invalid namespace: `%1$s`. Expecting: `...\\Classes`.', $this->namespace));
        }
        if (mb_substr($this->dir, -21) !== '/src/includes/classes') {
            throw new Exception(sprintf('Invalid dir: `%1$s`. Expecting: `.../src/includes/classes`.', $this->dir));
        }
        $this->Config = new Classes\Core\Base\Config($this, $instance_base, $instance, $args);
        $this->Di     = new Classes\Core\Base\Di($this, $this->Config->©di['©default_rule']);
        $this->Utils  = new Classes\Core\Base\Utils($this);

        $this->Di->addInstances([$this, $this->Config, $this->Utils]);

        $GLOBALS[$this->class] = $this;

        $GLOBALS[$this->namespace.'\\CoreFacades'] = $this;
        $this->facades['c']                        = $this->namespace.'\\CoreFacades';
        $_base_class                               = Classes\Core\Base\Facades::class;
        eval('namespace '.$this->namespace.' { class CoreFacades extends \\'.$_base_class.' {} }');

        $GLOBALS[$this->namespace.'\\AppFacades'] = $this;
        $this->facades['a']                       = $this->namespace.'\\AppFacades';
        $_base_class                              = class_exists($this->namespace.'\\Base\\Facades') ? $this->namespace.'\\Base\\Facades' : 'StdClass';
        eval('namespace '.$this->namespace.' { class AppFacades extends \\'.$_base_class.' {} }');

        foreach ($this->Config->©sub_namespace_map as $_sub_namespace => $_identifiers) {
            if ($_sub_namespace && !empty($_identifiers['©facades']) && class_exists($_base_class = $this->getClass($this->namespace.'\\'.$_sub_namespace.'\\Base\\Facades'))) {
                $GLOBALS[$this->namespace.'\\'.$_sub_namespace.'Facades'] = $this;
                $this->facades[$_identifiers['©facades']]                 = $this->namespace.'\\'.$_sub_namespace.'Facades';
                eval('namespace '.$this->namespace.' { class '.$_sub_namespace.'Facades extends \\'.$_base_class.' {} }');
            }
        } // unset($_sub_namespace, $_identifiers, $_base_class);

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
        if ($this->Config->©debug) {
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
        if (!$this->Config->©debug && $this->Config->©handle_exceptions) {
            // Handle exceptions w/ a custom error page.
            $this->c::setupExceptionHandler();
        }
    }

    /**
     * Maybe setup locales.
     *
     * @since 150424 Initial release.
     */
    protected function maybeSetLocales()
    {
        if ($this->Config->©i18n['©locales']) {
            // Try locale codes in a specific order.
            // See: <http://php.net/manual/en/function.setlocale.php>
            setlocale(LC_ALL, $this->Config->©i18n['©locales']);
        }
    }

    /**
     * Get class (in order of precedence).
     *
     * @since 160224 Support ancestors.
     *
     * @param string $class The class path.
     *
     * @return string Class w/ highest precedence.
     */
    public function getClass(string $class): string
    {
        if (!($classes_pos = strripos($class, '\\Classes\\'))) {
            return $class; // Return as-is.
        }
        if (!($sub_class = substr($class, $classes_pos + 9))) {
            return $class; // Return as-is.
        }
        if (class_exists($this->namespace.'\\'.$sub_class)) {
            return $class = $this->namespace.'\\'.$sub_class;
        } elseif (($_parent = $this->parent)) {
            do {
                if (class_exists($_parent->namespace.'\\'.$sub_class)) {
                    return $class = $_parent->namespace.'\\'.$sub_class;
                }
            } while ($_parent->parent); // unset($_parent);
        }
        return $class; // Return as-is.
    }
}
