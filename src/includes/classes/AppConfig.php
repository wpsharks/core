<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App config.
 *
 * @since 15xxxx Initial release.
 */
class AppConfig extends AbsCore
{
    /**
     * App.
     *
     * @since 15xxxx
     *
     * @type App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $instance Instance properties.
     */
    public function __construct(App $App, array $instance = [])
    {
        parent::__construct();

        $this->App = $App;

        # Instance base (i.e., default config).

        $instance_base = [
            'debug' => false,

            'di' => [
                'default_rule' => [
                    'new_instances' => [
                        self::class,
                        AppDi::class,
                        AppUtils::class,
                        CliOpts::class,
                        Exception::class,
                        Template::class,
                    ],
                ],
            ],
            'db_shards' => [
                'common' => [
                    'port'    => 0,
                    'charset' => '',

                    'username' => '',
                    'password' => '',

                    'ssl_enable' => false,
                    'ssl_ca'     => '',
                    'ssl_crt'    => '',
                    'ssl_key'    => '',
                    'ssl_cipher' => '',
                ],
                'dbs' => [
                    [
                        'range' => [
                            'from' => 0,
                            'to'   => 65535,
                        ],
                        'properties' => [
                            'host' => '',
                            'name' => '',
                        ],
                    ],
                ],
            ],
            'brand' => [
                'name'        => '',
                'acronym'     => '',
                'keywords'    => [],
                'description' => '',
                'tagline'     => '',
                'screenshot'  => '',
            ],
            'urls' => [
                'hosts' => [
                    'roots' => [
                        'app' => '',
                    ],
                    'app'    => '',
                    'cdn'    => '',
                    'cdn_s3' => '',
                ],
                'default_scheme' => '',
            ],
            'fs_paths' => [
                'cache_dir'     => '',
                'templates_dir' => '',
                'config_file'   => '',
            ],
            'i18n' => [
                'locales'     => ['en_US.UTF-8', 'C'],
                'text_domain' => '', // Off by default.
            ],
            'email' => [
                'from_name'  => '',
                'from_email' => '',

                'reply_to_name'  => '',
                'reply_to_email' => '',

                'smtp_host'     => '',
                'smtp_port'     => 0,
                'smtp_secure'   => '',
                'smtp_username' => '',
                'smtp_password' => '',
            ],
            'aws' => [
                'access_key' => '',
                'secret_key' => '',
            ],
            'hash_ids' => [
                'key' => '',
            ],
            'embedly' => [
                'api_key' => '',
            ],
            'web_purify' => [
                'api_key' => '',
            ],
        ];
        # Merge instance base w/ constructor instance.

        $instance = $config = static::merge($instance_base, $instance);

        # Merge a possible JSON configuration file also.

        if ($instance['fs_paths']['config_file']) {
            if (!is_file($instance['fs_paths']['config_file'])) {
                throw new Exception(sprintf('Missing config file: `%1$s`.', $instance['fs_paths']['config_file']));
            } elseif (!is_array($config = json_decode(file_get_contents($instance['fs_paths']['config_file']), true))) {
                throw new Exception(sprintf('Invalid config file: `%1$s`.', $instance['fs_paths']['config_file']));
            }
            $config = static::merge($instance, $config, true);
        }
        # Fill replacement codes and overload the config properties.

        $this->overload(($config = (object) $this->fillReplacementCodes($config)), true);

        # Initialize.

        $this->maybeDebug();
        $this->maybeSetLocales();
    }

    /**
     * Merge config into a base.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $base      Base array.
     * @param array $merge     Array to merge.
     * @param bool  $is_config Is config file?
     *
     * @return array The resuling array after merging.
     *
     * @note This is static so that app extenders can use it too.
     */
    public static function merge(array $base, array $merge, bool $is_config = false): array
    {
        if ($is_config) { // Disallow these instance-only keys.
            unset($merge['di'], $merge['fs_paths']['config_file']);
        }
        if (isset($base['di']['default_rule']['new_instances'])) {
            $base_di_default_rule_new_instances = $base['di']['default_rule']['new_instances'];
        } // Save new instances before emptying numeric arrays.

        $base = static::mergeMaybeEmptyNumericArrays($base, $merge);

        if (isset($base_di_default_rule_new_instances, $merge['di']['default_rule']['new_instances'])) {
            $merge['di']['default_rule']['new_instances'] = array_merge($base_di_default_rule_new_instances, $merge['di']['default_rule']['new_instances']);
        }
        return ($merged = array_replace_recursive($base, $merge)); // See: <http://php.net/manual/en/function.array-replace-recursive.php>
    }

    /**
     * Empty numeric arrays being extended.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $base  Base array.
     * @param array $merge Array to merge.
     *
     * @return array `$base` w/ empty numeric arrays being extended by `$merge`.
     */
    protected static function mergeMaybeEmptyNumericArrays(array $base, array $merge): array
    {
        if (!$merge) { // Save time. Merge is empty?
            return $base; // Nothing to do here.
        }
        foreach ($base as $_key => &$_value) {
            if (array_key_exists($_key, $merge) && is_array($_value)) {
                if (!$_value || $_value === array_values($_value)) {
                    $_value = []; // Replace all keys in numeric arrays.
                } elseif ($merge[$_key] && is_array($merge[$_key])) { // Recursive.
                    $_value = static::mergeMaybeEmptyNumericArrays($_value, $merge[$_key]);
                }
            }
        } // unset($_key, $_value); // Housekeeping.
        return $base; // Return possibly-modified base.
    }

    /**
     * Maybe setup debugging.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed Input value to iterate.
     *
     * @return mixed string|array|object Output value.
     */
    protected function fillReplacementCodes($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->fillReplacementCodes($_value);
            } // unset($_key, $_value); // Housekeeping.
        } elseif (is_string($value)) {
            $value = str_replace(
                ['%%app_ns%%', '%%app_dir%%'],
                [$this->App->ns, $this->App->dir],
                $value
            );
        }
        return $value; // With replacement codes filled now.
    }

    /**
     * Maybe setup debugging.
     *
     * @since 15xxxx Initial release.
     */
    protected function maybeDebug()
    {
        if ($this->debug) {
            // All errros.
            error_reporting(E_ALL);

            // Display errors.
            ini_set('display_errors', 'yes');

            // Fail softly, because it can only go from `0` to `1`.
            // If the current value is `-1` this will trigger a warning.
            @ini_set('zend.assertions', '1');
        }
    }

    /**
     * Maybe setup locales.
     *
     * @since 15xxxx Initial release.
     */
    protected function maybeSetLocales()
    {
        if ($this->i18n['locales']) {

            // Try locale codes in a specific order.
            // See: <http://php.net/manual/en/function.setlocale.php>
            setlocale(LC_ALL, $this->i18n['locales']);
        }
    }
}
