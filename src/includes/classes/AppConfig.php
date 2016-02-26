<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App config.
 *
 * @since 150424 Initial release.
 */
class AppConfig extends Core
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param App   $App           Instance of App.
     * @param array $instance_base Instance base.
     * @param array $instance      Instance args (highest precedence).
     * @param array $args          Any additional behavioral args.
     */
    public function __construct(App $App, array $instance_base = [], array $instance = [], array $args = [])
    {
        parent::__construct($App);

        # Establish arguments.

        $default_args = [
            'use_server_cfgs' => true,
        ];
        $args = array_merge($default_args, $args);

        # Instance base (i.e., default config).

        $_s_cfgs     = $args['use_server_cfgs'] ? $_SERVER : [];
        $host        = $_s_cfgs['CFG_HOST'] ?? mb_strtolower(php_uname('n'));
        $root_host   = $_s_cfgs['CFG_ROOT_HOST'] ?? implode('.', array_slice(explode('.', $host), -2));
        $is_cfg_host = !empty($_s_cfgs['CFG_HOST']); // Flag used below in some defaults.

        $default_instance_base = [
            '©debug'             => (bool) ($_s_cfgs['CFG_DEBUG'] ?? false),
            '©handle_exceptions' => (bool) ($_s_cfgs['CFG_HANDLE_EXCEPTIONS'] ?? false),

            '©contacts' => [
                '©admin' => [
                    '©name'         => (string) ($_s_cfgs['CFG_ADMIN_NAME'] ?? $_s_cfgs['CFG_ADMIN_USERNAME'] ?? 'admin'),
                    '©email'        => (string) ($_s_cfgs['CFG_ADMIN_EMAIL'] ?? $_s_cfgs['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$root_host),
                    '©public_email' => (string) ($_s_cfgs['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$root_host),
                ],
            ],

            '©di' => [
                '©default_rule' => [
                    'new_instances' => [
                        CliOpts::class,
                        Exception::class,
                        Template::class,
                        Tokenizer::class,
                    ],
                ],
            ],

            '©mysql_db' => [
                '©hosts' => [
                    (string) ($_s_cfgs['CFG_MYSQL_DB_HOST'] ?? '127.0.0.1') => [
                        '©port'    => (int) ($_s_cfgs['CFG_MYSQL_DB_PORT'] ?? 3306),
                        '©charset' => (string) ($_s_cfgs['CFG_MYSQL_DB_CHARSET'] ?? 'utf8mb4'),
                        '©collate' => (string) ($_s_cfgs['CFG_MYSQL_DB_COLLATE'] ?? 'utf8mb4_unicode_ci'),

                        '©username' => (string) ($_s_cfgs['CFG_MYSQL_DB_USERNAME'] ?? 'client'),
                        '©password' => (string) ($_s_cfgs['CFG_MYSQL_DB_PASSWORD'] ?? ''),

                        '©ssl_enable' => (bool) ($_s_cfgs['CFG_MYSQL_SSL_ENABLE'] ?? false),
                        '©ssl_key'    => (string) ($_s_cfgs['CFG_MYSQL_SSL_KEY'] ?? ''),
                        '©ssl_crt'    => (string) ($_s_cfgs['CFG_MYSQL_SSL_CRT'] ?? ''),
                        '©ssl_ca'     => (string) ($_s_cfgs['CFG_MYSQL_SSL_CA'] ?? ''),
                        '©ssl_cipher' => (string) ($_s_cfgs['CFG_MYSQL_SSL_CIPHER'] ?? ''),
                    ],
                ],
                '©shards' => [
                    [
                        '©range' => [
                            '©from' => 0,
                            '©to'   => 65535,
                        ],
                        '©properties' => [
                            '©host' => (string) ($_s_cfgs['CFG_MYSQL_DB_HOST'] ?? '127.0.0.1'),
                            '©name' => (string) ($_s_cfgs['CFG_MYSQL_DB_NAME'] ?? 'db0'),
                        ],
                    ],
                ],
            ],

            '©brand' => [
                '©slug'    => (string) ($_s_cfgs['CFG_BRAND_SLUG'] ?? $_s_cfgs['CFG_SLUG'] ?? 'app'),
                '©var'     => (string) ($_s_cfgs['CFG_BRAND_VAR'] ?? $_s_cfgs['CFG_VAR'] ?? 'app'),
                '©name'    => (string) ($_s_cfgs['CFG_BRAND_NAME'] ?? $_s_cfgs['CFG_HOST'] ?? $host),
                '©acronym' => (string) ($_s_cfgs['CFG_BRAND_ACRONYM'] ?? 'APP'),
                '©prefix'  => (string) ($_s_cfgs['CFG_BRAND_PREFIX'] ?? 'app'),

                '©keywords'    => (string) ($_s_cfgs['CFG_BRAND_KEYWORDS'] ?? ''),
                '©description' => (string) ($_s_cfgs['CFG_BRAND_DESCRIPTION'] ?? ''),
                '©tagline'     => (string) ($_s_cfgs['CFG_BRAND_TAGLINE'] ?? ''),

                '©favicon'    => (string) ($_s_cfgs['CFG_BRAND_FAVICON'] ?? '/favicon.ico'),
                '©logo'       => (string) ($_s_cfgs['CFG_BRAND_LOGO'] ?? ''),
                '©screenshot' => (string) ($_s_cfgs['CFG_BRAND_SCREENSHOT'] ?? ''),
            ],

            '©urls' => [
                '©hosts' => [
                    '©roots' => [
                        '©app' => (string) ($_s_cfgs['CFG_ROOT_HOST'] ?? $root_host),
                    ],
                    '©app'    => (string) ($_s_cfgs['CFG_HOST'] ?? $host),
                    '©cdn'    => (string) ($_s_cfgs['CFG_CDN_HOST'] ?? 'cdn.'.$root_host),
                    '©cdn_s3' => (string) ($_s_cfgs['CFG_CDN_S3_HOST'] ?? 'cdn-s3.'.$root_host),
                ],
                '©cdn_filter_enable' => (bool) ($_s_cfgs['CFG_CDN_FILTER_ENABLE'] ?? false),
                '©default_scheme'    => (string) ($_s_cfgs['CFG_DEFAULT_URL_SCHEME'] ?? 'https'),
                '©sig_key'           => (string) ($_s_cfgs['CFG_URL_SIG_KEY'] ?? ''),
            ],

            '©fs_paths' => [
                '©logs_dir'      => (string) ($_s_cfgs['CFG_LOGS_DIR'] ?? '/var/log/%%app_namespace_sha1%%'),
                '©cache_dir'     => (string) ($_s_cfgs['CFG_CACHE_DIR'] ?? '/tmp/%%app_namespace_sha1%%/cache'),
                '©templates_dir' => (string) ($_s_cfgs['CFG_TEMPLATES_DIR'] ?? '%%app_dir%%/src/includes/templates'),
                '©errors_dir'    => (string) ($_s_cfgs['CFG_ERRORS_DIR'] ?? ($is_cfg_host ? '/bootstrap/src/html/errors' : '')),
                '©config_file'   => (string) ($_s_cfgs['CFG_CONFIG_FILE'] ?? '%%app_dir%%/.config.json'),
            ],
            '©fs_permissions' => [
                '©transient_dirs' => (int) ($_s_cfgs['CFG_TRANSIENT_DIR_PERMISSIONS'] ?? 02775),
                // `octdec(02775)` = 1533 as an integer.
            ],

            '©memcache' => [
                '©enabled'   => (bool) ($_s_cfgs['CFG_MEMCACHE_ENABLED'] ?? $is_cfg_host),
                '©namespace' => (string) ($_s_cfgs['CFG_MEMCACHE_NAMESPACE'] ?? 'app'),
                '©servers'   => [
                    [
                        '©host'   => (string) ($_s_cfgs['CFG_MEMCACHE_HOST'] ?? '127.0.0.1'),
                        '©port'   => (int) ($_s_cfgs['CFG_MEMCACHE_PORT'] ?? 11211),
                        '©weight' => (int) ($_s_cfgs['CFG_MEMCACHE_WEIGHT'] ?? 0),
                    ],
                ],
            ],

            '©i18n' => [
                '©locales'     => (array) ($_s_cfgs['CFG_LOCALES'] ?? ($is_cfg_host ? ['en_US.UTF-8', 'C'] : [])),
                '©text_domain' => (string) ($_s_cfgs['CFG_I18N_TEXT_DOMAIN'] ?? ''),
            ],

            '©email' => [
                '©from_name'  => (string) ($_s_cfgs['CFG_EMAIL_FROM_NAME'] ?? $_s_cfgs['CFG_BRAND_NAME'] ?? $_s_cfgs['CFG_HOST'] ?? 'App'),
                '©from_email' => (string) ($_s_cfgs['CFG_EMAIL_FROM_EMAIL'] ?? $_s_cfgs['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'app@'.$root_host),

                '©reply_to_name'  => (string) ($_s_cfgs['CFG_EMAIL_REPLY_TO_NAME'] ?? ''),
                '©reply_to_email' => (string) ($_s_cfgs['CFG_EMAIL_REPLY_TO_EMAIL'] ?? ''),

                '©smtp_host'   => (string) ($_s_cfgs['CFG_EMAIL_SMTP_HOST'] ?? '127.0.0.1'),
                '©smtp_port'   => (int) ($_s_cfgs['CFG_EMAIL_SMTP_PORT'] ?? 25),
                '©smtp_secure' => (string) ($_s_cfgs['CFG_EMAIL_SMTP_SECURE'] ?? ''),

                '©smtp_username' => (string) ($_s_cfgs['CFG_EMAIL_SMTP_USERNAME'] ?? ''),
                '©smtp_password' => (string) ($_s_cfgs['CFG_EMAIL_SMTP_PASSWORD'] ?? ''),
            ],

            '©cookies' => [
                '©encryption_key' => (string) ($_s_cfgs['CFG_COOKIES_KEY'] ?? ''),
            ],
            '©hash_ids' => [
                '©hash_key' => (string) ($_s_cfgs['CFG_HASH_IDS_KEY'] ?? ''),
            ],
            '©passwords' => [
                '©hash_key' => (string) ($_s_cfgs['CFG_PASSWORDS_KEY'] ?? ''),
            ],

            '©aws' => [
                '©access_key' => (string) ($_s_cfgs['CFG_AWS_ACCESS_KEY'] ?? ''),
                '©secret_key' => (string) ($_s_cfgs['CFG_AWS_SECRET_KEY'] ?? ''),
            ],
            '©embedly' => [
                '©api_key' => (string) ($_s_cfgs['CFG_EMBEDLY_KEY'] ?? ''),
            ],
            '©webpurify' => [
                '©api_key' => (string) ($_s_cfgs['CFG_WEBPURIFY_KEY'] ?? ''),
            ],
            '©bitly' => [
                '©api_key' => (string) ($_s_cfgs['CFG_BITLY_KEY'] ?? ''),
            ],
        ];
        # Merge instance bases together now.

        $instance_base = $this->merge($default_instance_base, $instance_base);

        # Merge a possible JSON configuration file also.
        // @TODO Store config in memory to avoid repeated disk reads.

        $config_file = $instance['©fs_paths']['©config_file'] ?? $instance_base['©fs_paths']['©config_file'];

        if ($config_file && is_file($config_file)) { // Has a config file?
            if (!is_array($config = json_decode(file_get_contents($config_file), true))) {
                throw new Exception(sprintf('Invalid config file: `%1$s`.', $config_file));
            }
            if (!empty($config['©core_app'])) {
                $config = (array) $config['©core_app'];
            } elseif (!empty($config['©app'])) {
                $config = (array) $config['©app'];
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
     * @since 150424 Initial release.
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
            unset($merge['©di'], $merge['©fs_paths']['©config_file']);
        }
        if (isset($base['©di']['©default_rule']['new_instances'])) {
            $base_di_default_rule_new_instances = $base['©di']['©default_rule']['new_instances'];
        } // Save new instances before emptying numeric arrays.

        if (isset($merge['©mysql_db']['©hosts'])) {
            unset($base['©mysql_db']['©hosts']);
        } // Override base array. Replace w/ new hosts only.

        $base = $this->maybeEmptyNumericArrays($base, $merge);

        if (isset($base_di_default_rule_new_instances, $merge['©di']['©default_rule']['new_instances'])) {
            $merge['©di']['©default_rule']['new_instances'] = array_merge($base_di_default_rule_new_instances, $merge['©di']['©default_rule']['new_instances']);
        }
        return $merged = array_replace_recursive($base, $merge);
    }

    /**
     * Empty numeric arrays.
     *
     * @since 150424 Initial release.
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
     * @since 150424 Initial release.
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
        if ($value && is_string($value) && mb_strpos($value, '%%') !== false) {
            $value = str_replace(
                [
                    '%%app_class%%',
                    '%%app_class_sha1%%',

                    '%%app_namespace%%',
                    '%%app_namespace_sha1%%',

                    '%%app_dir%%',
                    '%%app_dir_basename%%',
                    '%%app_dir_sha1%%',

                    '%%core_dir%%',
                    '%%core_dir_basename%%',
                    '%%core_dir_sha1%%',

                    '%%home_dir%%',
                ],
                [
                    $this->App->class,
                    $this->App->class_sha1,

                    $this->App->namespace,
                    $this->App->namespace_sha1,

                    $this->App->dir,
                    $this->App->dir_basename,
                    $this->App->dir_sha1,

                    $this->App->core_dir,
                    $this->App->core_dir_basename,
                    $this->App->core_dir_sha1,

                    (string) ($_SERVER['HOME'] ?? ''),
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
