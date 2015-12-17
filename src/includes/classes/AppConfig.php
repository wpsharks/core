<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
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
     * @param array $instance_base Instance base.
     * @param array $instance      Instance args (highest precedence).
     */
    public function __construct(App $App, array $instance_base = [], array $instance = [])
    {
        parent::__construct();

        $this->App = $App;

        # Instance base (i.e., default config).

        $default_instance_base = [
            'debug'             => false,
            'handle_exceptions' => true,

            'di' => [
                'default_rule' => [
                    'new_instances' => [
                        self::class,
                        AppDi::class,
                        Utils::class,
                        CliOpts::class,
                        Exception::class,
                        Template::class,
                        Tokenizer::class,
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
                'favicon'     => '',
                'logo'        => '',
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
                'sig_key'        => '',
            ],
            'fs_paths' => [
                'cache_dir'     => '',
                'templates_dir' => '',
                'config_file'   => '',
            ],
            'fs_permissions' => [
                'transient_dirs' => 0777,
                // `0777` = `511` integer.
            ],
            'memcache' => [
                'enabled'   => true,
                'namespace' => 'app',
                'servers'   => [
                    [
                        'host'   => '127.0.0.1',
                        'port'   => 11211,
                        'weight' => 0,
                    ],
                ],
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
            'cookies' => [
                'key' => '',
            ],
            'hash_ids' => [
                'key' => '',
            ],
            'passwords' => [
                'key' => '',
            ],
            'aws' => [
                'access_key' => '',
                'secret_key' => '',
            ],
            'embedly' => [
                'api_key' => '',
            ],
            'web_purify' => [
                'api_key' => '',
            ],
        ];
        # Merge instance bases together now.

        $instance_base = $this->merge($default_instance_base, $instance_base);

        # Merge a possible JSON configuration file also.
        // @TODO Store config in memory to avoid repeated disk reads.

        if (($config_file = (string) ($instance['fs_paths']['config_file'] ?? ''))) {
            if (!is_file($config_file)) {
                throw new Exception(sprintf('Missing config file: `%1$s`.', $config_file));
            } elseif (!is_array($config = json_decode(file_get_contents($config_file), true))) {
                throw new Exception(sprintf('Invalid config file: `%1$s`.', $config_file));
            }
            $config = $this->merge($instance_base, $config, true);
            $config = $this->merge($config, $instance);
        } else {
            $config = $this->merge($instance_base, $instance);
        }
        # Fill replacement codes and overload the config properties.

        $this->overload((object) $this->fillReplacementCodes($config), true);
    }

    /**
     * Merge config arrays.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $base      Base array.
     * @param array $merge     Array to merge.
     * @param bool  $is_config Is config file?
     *
     * @return array The resuling array after merging.
     */
    protected function merge(array $base, array $merge, bool $is_config = false): array
    {
        if ($is_config) { // Disallow these instance-only keys.
            unset($merge['di'], $merge['fs_paths']['config_file']);
        }
        if (isset($base['di']['default_rule']['new_instances'])) {
            $base_di_default_rule_new_instances = $base['di']['default_rule']['new_instances'];
        } // Save new instances before emptying numeric arrays.

        $base = $this->maybeEmptyNumericArrays($base, $merge); // Maybe empty numeric arrays.

        if (isset($base_di_default_rule_new_instances, $merge['di']['default_rule']['new_instances'])) {
            $merge['di']['default_rule']['new_instances'] = array_merge($base_di_default_rule_new_instances, $merge['di']['default_rule']['new_instances']);
        }
        return $merged = array_replace_recursive($base, $merge);
    }

    /**
     * Empty numeric arrays.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $base  Base array.
     * @param array $merge Array to merge.
     *
     * @return array The `$base` w/ possibly-empty numeric arrays.
     */
    protected function maybeEmptyNumericArrays(array $base, array $merge): array
    {
        if (!$merge) { // Save time. Merge is empty?
            return $base; // Nothing to do here.
        }
        foreach ($base as $_key => &$_value) {
            if (is_array($_value) && array_key_exists($_key, $merge)) {
                if (!$_value || $_value === array_values($_value)) {
                    $_value = []; // Empty numeric arrays.
                } elseif ($merge[$_key] && is_array($merge[$_key])) {
                    $_value = $this->maybeEmptyNumericArrays($_value, $merge[$_key]);
                }
            }
        } // unset($_key, $_value); // Housekeeping.
        return $base; // Return possibly-modified base.
    }

    /**
     * Fill replacement codes.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $value Input value.
     *
     * @return mixed string|array|object Output value.
     */
    protected function fillReplacementCodes($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->fillReplacementCodes($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if ($value && is_string($value)) {
            $value = str_replace(
                [
                    '%%app_namespace%%',
                    '%%app_namespace_sha1%%',
                    '%%app_dir%%',
                    '%%core_dir%%',
                ],
                [
                    $this->App->namespace,
                    $this->App->namespace_sha1,
                    $this->App->dir,
                    $this->App->core_dir,
                ],
                $value
            );
        }
        return $value;
    }
}
