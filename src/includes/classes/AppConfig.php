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
    public function __construct(array $instance_base = [], array $instance = [])
    {
        parent::__construct();

        $this->App = $GLOBALS[App::class];

        # Instance base (i.e., default config).

        $default_instance_base = [
            'debug'             => $_SERVER['DEBUG'] ?? false,
            'handle_exceptions' => $_SERVER['HANDLE_EXCEPTIONS'] ?? false,
            'contacts'          => [
                'admin' => [
                    'name'  => $_SERVER['ADMIN_NAME'] ?? 'Admin',
                    'email' => $_SERVER['ADMIN_EMAIL'] ?? 'admin@'.$this->App->server_name,
                ],
            ],
            'di' => [
                'default_rule' => [
                    'new_instances' => [
                        CliOpts::class,
                        Exception::class,
                        Template::class,
                        Tokenizer::class,
                    ],
                ],
            ],
            'db_shards' => [
                'common' => [
                    'port'    => $_SERVER['MYSQL_DB_PORT'] ?? 3306,
                    'charset' => $_SERVER['MYSQL_DB_CHARSET'] ?? 'utf8mb4',
                    'collate' => $_SERVER['MYSQL_DB_COLLATE'] ?? 'utf8mb4_unicode_ci',

                    'username' => $_SERVER['MYSQL_DB_USER'] ?? 'root',
                    'password' => $_SERVER['MYSQL_DB_PASSWORD'] ?? '',

                    'ssl_enable' => $_SERVER['MYSQL_SSL_ENABLE'] ?? false,
                    'ssl_ca'     => $_SERVER['MYSQL_SSL_CA'] ?? '%%app_dir%%/assets/ssl/ca.vm.crt',
                    'ssl_crt'    => $_SERVER['MYSQL_SSL_CRT'] ?? '%%app_dir%%/assets/ssl/client.crt',
                    'ssl_key'    => $_SERVER['MYSQL_SSL_KEY'] ?? '%%app_dir%%/assets/ssl/client.key',
                    'ssl_cipher' => $_SERVER['MYSQL_SSL_CIPHER'] ?? 'CAMELLIA256-SHA',
                ],
                'dbs' => [
                    [
                        'range' => [
                            'from' => 0,
                            'to'   => 65535,
                        ],
                        'properties' => [
                            'host' => $_SERVER['MYSQL_DB_HOST'] ?? '127.0.0.1',
                            'name' => $_SERVER['MYSQL_DB_NAME'] ?? 'db0',
                        ],
                    ],
                ],
            ],
            'brand' => [
                'acronym'     => $_SERVER['BRAND_ACRONYM'] ?? 'APP',
                'name'        => $_SERVER['BRAND_NAME'] ?? $this->App->server_name,
                'keywords'    => $_SERVER['BRAND_KEYWORDS'] ?? [$this->App->server_name],
                'description' => $_SERVER['BRAND_DESCRIPTION'] ?? 'Just another site powered by the websharks/core.',
                'tagline'     => $_SERVER['BRAND_TAGLINE'] ?? 'Powered by the websharks/core.',
                'screenshot'  => $_SERVER['BRAND_SCREENSHOT'] ?? '/client-s/images/screenshot.png',
                'favicon'     => $_SERVER['BRAND_FAVICON'] ?? '/client-s/images/favicon.ico',
                'logo'        => $_SERVER['BRAND_LOGO'] ?? '/client-s/images/logo.png',
            ],
            'urls' => [
                'hosts' => [
                    'roots' => [
                        'app' => $_SERVER['APP_ROOT_HOST'] ?? $this->App->server_root_name,
                    ],
                    'app'    => $_SERVER['APP_HOST'] ?? $this->App->server_name,
                    'cdn'    => $_SERVER['CDN_HOST'] ?? 'cdn.'.$this->App->server_root_name,
                    'cdn_s3' => $_SERVER['CDN_S3_HOST'] ?? 'cdn-s3.'.$this->App->server_root_name,
                ],
                'default_scheme' => $_SERVER['DEFAULT_URL_SCHEME'] ?? 'https',
                'sig_key'        => $_SERVER['URL_SIG_KEY'] ?? '',
            ],
            'fs_paths' => [
                'cache_dir'     => $_SERVER['CACHE_DIR'] ?? '%%app_dir%%/.~cache',
                'templates_dir' => $_SERVER['TEMPLATES_DIR'] ?? '%%app_dir%%/src/includes/templates',
                'config_file'   => $_SERVER['CONFIG_FILE'] ?? '',
            ],
            'fs_permissions' => [
                'transient_dirs' => $_SERVER['TRANSIENT_DIR_PERMISSIONS'] ?? 0777,
                // `0777` = `511` integer.
            ],
            'memcache' => [
                'enabled'   => $_SERVER['MEMCACHE_ENABLED'] ?? true,
                'namespace' => $_SERVER['MEMCACHE_NAMESPACE'] ?? 'app',
                'servers'   => [
                    [
                        'host'   => $_SERVER['MEMCACHE_HOST'] ?? '127.0.0.1',
                        'port'   => $_SERVER['MEMCACHE_PORT'] ?? 11211,
                        'weight' => $_SERVER['MEMCACHE_WEIGHT'] ?? 0,
                    ],
                ],
            ],
            'i18n' => [
                'locales'     => $_SERVER['LOCALES'] ?? ['en_US.UTF-8', 'C'],
                'text_domain' => $_SERVER['I18N_TEXT_DOMAIN'] ?? 'app',
            ],
            'email' => [
                'from_name'  => $_SERVER['EMAIL_FROM_NAME'] ?? 'App',
                'from_email' => $_SERVER['EMAIL_FROM_EMAIL'] ?? 'app@'.$this->App->server_name,

                'reply_to_name'  => $_SERVER['EMAIL_REPLY_TO_NAME'] ?? '',
                'reply_to_email' => $_SERVER['EMAIL_REPLY_TO_EMAIL'] ?? '',

                'smtp_host'   => $_SERVER['EMAIL_SMTP_HOST'] ?? '127.0.0.1',
                'smtp_port'   => $_SERVER['EMAIL_SMTP_PORT'] ?? 25,
                'smtp_secure' => $_SERVER['EMAIL_SMTP_SECURE'] ?? '',

                'smtp_username' => $_SERVER['EMAIL_SMTP_USERNAME'] ?? '',
                'smtp_password' => $_SERVER['EMAIL_SMTP_PASSWORD'] ?? '',
            ],
            'cookies' => [
                'key' => $_SERVER['COOKIES_KEY'] ?? '',
            ],
            'hash_ids' => [
                'key' => $_SERVER['HASH_IDS_KEY'] ?? '',
            ],
            'passwords' => [
                'key' => $_SERVER['PASSWORDS_KEY'] ?? '',
            ],
            'aws' => [
                'access_key' => $_SERVER['AWS_ACCESS_KEY'] ?? '',
                'secret_key' => $_SERVER['AWS_SECRET_KEY'] ?? '',
            ],
            'embedly' => [
                'api_key' => $_SERVER['EMBEDLY_KEY'] ?? '',
            ],
            'web_purify' => [
                'api_key' => $_SERVER['WEBPURIFY_KEY'] ?? '',
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
