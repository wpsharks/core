<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

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
    public $Parent;

    /**
     * Reflection.
     *
     * @since 160228
     *
     * @type \ReflectionClass
     */
    public $Reflection;

    /**
     * Class.
     *
     * @since 160223
     *
     * @type string
     */
    public $class;

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
     * WSC dir.
     *
     * @since 150424
     *
     * @type string
     */
    public $ws_core_dir;

    /**
     * Is WSC?
     *
     * @since 150424
     *
     * @type bool
     */
    public $is_ws_core;

    /**
     * Is a core?
     *
     * @since 150424
     *
     * @type bool
     */
    public $is_core;

    /**
     * Facades.
     *
     * @since 160227
     *
     * @type \StdClass
     */
    public $Facades;

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
     * Version.
     *
     * @since 150424
     *
     * @type string Version.
     */
    const VERSION = '160713.3972'; //v//

    /**
     * Constructor.
     *
     * @since 150424 Initial release.
     *
     * @param array            $instance_base Instance base.
     * @param array            $instance      Instance args.
     * @param Classes\App|null $Parent        Parent app (optional).
     */
    public function __construct(array $instance_base = [], array $instance = [], Classes\App $Parent = null)
    {
        parent::__construct();

        # Establish debug mode early-on.

        if (isset($instance['©debug']['©enable'])) {
            $debug = (bool) $instance['©debug']['©enable'];
        } elseif (isset($instance_base['©debug']['©enable'])) {
            $debug = (bool) $instance_base['©debug']['©enable'];
        } else { // Based on server CFGs (if enabled).
            $use_server_cfgs = (bool) ($instance['©use_server_cfgs']
                ?? $instance_base['©use_server_cfgs'] ?? !empty($_SERVER['CFG_HOST']));
            $debug = $use_server_cfgs && !empty($_SERVER['CFG_DEBUG']);
        }
        # Define a possible parent app.

        $this->Parent = $Parent;

        # Define reflection-based properties.
        // If not already obtained by an extender.

        $this->Reflection = $this->Reflection ?: new \ReflectionClass($this);

        $this->class          = $this->class ?: $this->Reflection->getName();
        $this->namespace      = $this->namespace ?: $this->Reflection->getNamespaceName();
        $this->namespace_sha1 = $this->namespace_sha1 ?: sha1($this->namespace);

        $this->file         = $this->file ?: $this->Reflection->getFileName();
        $this->dir          = $this->dir ?: dirname($this->file);
        $this->dir_basename = $this->dir_basename ?: basename($this->dir);

        $this->base_dir          = $this->base_dir ?: dirname($this->dir, 3);
        $this->base_dir_basename = $this->base_dir_basename ?: basename($this->base_dir);

        $this->ws_core_dir = $this->ws_core_dir ?: dirname(__FILE__, 4);

        $this->is_ws_core = $this->class === self::class;
        $this->is_core    = $this->is_ws_core || !$this->Parent;

        # Validate app instance (if applicable).

        if ($debug) { // Only in debug mode.
            if (isset($GLOBALS[$this->class])) {
                throw new Exception(sprintf('One instance of `%1$s` only please.', $this->class));
            } elseif (mb_substr($this->namespace, -8) !== '\\Classes') {
                throw new Exception(sprintf('Invalid namespace: `%1$s`. Expecting: `...\\Classes`.', $this->namespace));
            } elseif (mb_substr($this->dir, -21) !== '/src/includes/classes') {
                throw new Exception(sprintf('Invalid dir: `%1$s`. Expecting: `.../src/includes/classes`.', $this->dir));
            }
        }
        # Instantiate/configure child class instances; e.g., Config, Di, Utils.

        $this->Config = new Classes\Core\Base\Config($this, $instance_base, $instance);
        $this->Di     = new Classes\Core\Base\Di($this, $this->Config->©di['©default_rule']);
        $this->Utils  = new Classes\Core\Base\Utils($this);

        $this->Di->addInstances([$this, $this->Config, $this->Utils]);

        # Setup global references & facades.
        # http://docstore.mik.ua/orelly/webprog/pcook/ch07_13.htm

        $GLOBALS[$this->class] = $this; // Global access variable.

        $GLOBALS[$this->Facades->c = $this->namespace.'\\CoreFacades'] = $this;
        $_facades_base_class                                           = $this->getClass(Classes\Core\Base\Facades::class);
        eval('namespace '.$this->namespace.' { class CoreFacades extends \\'.$_facades_base_class.' {} }');

        foreach ($this->Config->©sub_namespace_map as $_sub_namespace => $_identifiers) {
            if (class_exists($_facades_base_class = $this->getClass($this->namespace.'\\'.$_sub_namespace.'\\Base\\Facades'))) {
                $GLOBALS[$this->Facades->{$_identifiers['©facades']} = $this->namespace.'\\'.$_sub_namespace.'Facades'] = $this;
                eval('namespace '.$this->namespace.' { class '.$_sub_namespace.'Facades extends \\'.$_facades_base_class.' {} }');
            }
        } // unset($_sub_namespace, $_identifiers, $_facades_base_class);

        $GLOBALS[$this->Facades->a = $this->namespace.'\\AppFacades'] = $this;
        $_facades_base_class                                          = $this->namespace.'\\Base\\Facades';
        $_facades_base_class                                          = class_exists($_facades_base_class) ? $_facades_base_class : 'StdClass';
        eval('namespace '.$this->namespace.' { class AppFacades extends \\'.$_facades_base_class.' {} }');

        # Post-construct sub-routines.

        $this->maybeDebug();
        $this->maybeSetLocales();
        $this->maybeHandleThrowables();
    }

    /**
     * Maybe setup debug error reporting.
     *
     * @since 150424 Initial release.
     */
    protected function maybeDebug()
    {
        if (!$this->Config->©debug['©enable']) {
            return; // Nothing to do here.
        } elseif (!$this->Config->©debug['©er_enable']) {
            return; // Nothing to do here.
        }
        error_reporting(E_ALL); // Enable error reporting.

        if ($this->Config->©debug['©er_display']) {
            ini_set('display_errors', 'yes'); // Display.
        }
        if ($this->Config->©debug['©er_assertions']) {
            // Fail softly, because it can only go from `0` to `1`.
            // If the current value is `-1` this will trigger a warning.
            @ini_set('zend.assertions', '1');
            ini_set('assert.exception', 'yes');
        }
    }

    /**
     * Maybe handle throwables.
     *
     * @since 160711 Initial release.
     */
    protected function maybeHandleThrowables()
    {
        if (!$this->Config->©debug['©enable'] && $this->Config->©handle_throwables) {
            // Handle throwables w/ a custom error page.
            $this->c::setupThrowableHandler();
        }
    }

    /**
     * Maybe setup locales.
     *
     * @since 150424 Initial release.
     */
    protected function maybeSetLocales()
    {
        if ($this->Config->©locales) {
            // Try locale codes in a specific order.
            // See: <http://php.net/manual/en/function.setlocale.php>
            setlocale(LC_ALL, $this->Config->©locales);
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
        if (!($classes_position = mb_strripos($class, '\\Classes\\'))) {
            return $class; // Not possible; return as-is.
        } elseif (!($sub_class = mb_substr($class, $classes_position + 9))) {
            return $class; // Not possible; return as-is.
        }
        if (class_exists($this->namespace.'\\'.$sub_class)) {
            return $class = $this->namespace.'\\'.$sub_class;
        } elseif (($_Parent = $this->Parent)) {
            do {
                if (class_exists($_Parent->namespace.'\\'.$sub_class)) {
                    return $class = $_Parent->namespace.'\\'.$sub_class;
                }
            } while ($_Parent->Parent); // unset($_Parent);
        }
        return $class; // Return as-is (nothing more we can do).
    }

    /**
     * Merge config arrays.
     *
     * @since 160227 Config utils.
     *
     * @param array $base  Base array.
     * @param array $merge Array to merge.
     *
     * @return array The resuling array after merging.
     *
     * @note This method must be capable of running w/o a constructed app.
     */
    public function mergeConfig(array $base, array $merge): array
    {
        if (isset($base['©di']['©default_rule']['new_instances'])) {
            $base_di_default_rule_new_instances = $base['©di']['©default_rule']['new_instances'];
        } // Save new instances before emptying numeric arrays.

        if (isset($merge['©mysql_db']['©hosts'])) {
            unset($base['©mysql_db']['©hosts']);
        } // Override base array. Replace w/ new hosts.

        $base = $this->maybeEmptyNumericConfigArrays($base, $merge);

        if (isset($base_di_default_rule_new_instances, $merge['©di']['©default_rule']['new_instances'])) {
            $merge['©di']['©default_rule']['new_instances'] = array_merge($base_di_default_rule_new_instances, $merge['©di']['©default_rule']['new_instances']);
        }
        return $merged = array_replace_recursive($base, $merge);
    }

    /**
     * Empty numeric config arrays.
     *
     * @since 160227 Config utils.
     *
     * @param array $base  Base array.
     * @param array $merge Array to merge.
     *
     * @return array The `$base` w/ possibly-empty numeric arrays.
     *
     * @note This method must be capable of running w/o a constructed app.
     */
    public function maybeEmptyNumericConfigArrays(array $base, array $merge): array
    {
        if (!$merge) { // Save time. Merge is empty?
            return $base; // Nothing to do here.
        }
        foreach ($base as $_key => &$_value) {
            if (is_array($_value) && array_key_exists($_key, $merge)) {
                if (!$_value || $_value === array_values($_value)) {
                    $_value = []; // Empty numeric arrays.
                } elseif ($merge[$_key] && is_array($merge[$_key])) {
                    $_value = $this->maybeEmptyNumericConfigArrays($_value, $merge[$_key]);
                }
            }
        } // unset($_key, $_value); // Housekeeping.
        return $base; // Return possibly-modified base.
    }

    /**
     * Fill replacement codes.
     *
     * @since 150424 Config utils.
     *
     * @param mixed $value Input value.
     *
     * @return mixed string|array|object Output value.
     *
     * @note This method must be capable of running w/o a fully-constructed app.
     *  Only dependency is the initial reflection-based properties.
     */
    public function fillConfigReplacementCodes($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->fillConfigReplacementCodes($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if ($value && is_string($value) && mb_strpos($value, '%%') !== false) {
            $value = str_replace(
                [
                    '%%app_class%%',
                    '%%app_namespace%%',
                    '%%app_namespace_sha1%%',

                    '%%app_file%%',
                    '%%app_dir%%',
                    '%%app_dir_basename%%',

                    '%%app_base_dir%%',
                    '%%app_base_dir_basename%%',

                    '%%ws_core_dir%%',

                    '%%home_dir%%',
                ],
                [
                    $this->class,
                    $this->namespace,
                    $this->namespace_sha1,

                    $this->file,
                    $this->dir,
                    $this->dir_basename,

                    $this->base_dir,
                    $this->base_dir_basename,

                    $this->ws_core_dir,

                    (string) ($_SERVER['HOME'] ?? $_SERVER['WEBSHARK_HOME'] ?? ''),
                ],
                $value
            );
            if (mb_strpos($value, '%%env[') !== false) {
                // e.g., `%%(int)env[CFG_MYSQL_DB_PORT]%%`, `%%(array)env[CFG_LOCALES]%%`.
                $value = preg_replace_callback('/%%(\((?<type>[^()]+)\))?env\[(?<key>[^%[\]]+)\]%%/u', function ($m) {
                    $env_key_value = $_SERVER[$m['key']] ?? '';

                    if (!empty($m['type'])) {
                        settype($env_key_value, $m['type']);
                    } else {
                        $env_key_value = (string) $env_key_value;
                    }
                    return $env_key_value;
                });
            }
        }
        return $value;
    }
}
