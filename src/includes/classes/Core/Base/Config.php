<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Base;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Config.
 *
 * @since 150424 Initial release.
 */
class Config extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App           Instance of App.
     * @param array       $instance_base Instance base.
     * @param array       $instance      Instance args (highest precedence).
     * @param array       $args          Any additional behavioral args.
     */
    public function __construct(Classes\App $App, array $instance_base = [], array $instance = [], array $args = [])
    {
        parent::__construct($App);

        # Establish arguments.

        $default_args = [
            '©use_server_cfgs' => true,
        ];
        $args = array_merge($default_args, $args);

        # Instance base (i.e., default config).

        $_s_cfgs     = $args['©use_server_cfgs'] ? $_SERVER : [];
        $host        = $_s_cfgs['CFG_HOST'] ?? mb_strtolower(php_uname('n'));
        $root_host   = $_s_cfgs['CFG_ROOT_HOST'] ?? implode('.', array_slice(explode('.', $host), -2));
        $is_cfg_host = !empty($_s_cfgs['CFG_HOST']); // Flag used below in some defaults.

        $default_instance_base = [
            '©debug' => [
                '©enable' => (bool) ($_s_cfgs['CFG_DEBUG'] ?? false),
                '©edge'   => (bool) ($_s_cfgs['CFG_DEBUG_EDGE'] ?? false),

                '©log'          => (bool) ($_s_cfgs['CFG_DEBUG_LOG'] ?? $_s_cfgs['CFG_DEBUG'] ?? false),
                '©log_callback' => false, // In case log entries should be reviewed in additional ways.

                '©er_enable'     => (bool) ($_s_cfgs['CFG_DEBUG_ER_ENABLE'] ?? $_s_cfgs['CFG_DEBUG'] ?? false),
                '©er_display'    => (bool) ($_s_cfgs['CFG_DEBUG_ER_DISPLAY'] ?? $_s_cfgs['CFG_DEBUG_ER_ENABLE'] ?? $_s_cfgs['CFG_DEBUG'] ?? false),
                '©er_assertions' => (bool) ($_s_cfgs['CFG_DEBUG_ER_ASSERTIONS'] ?? $_s_cfgs['CFG_DEBUG_ER_ENABLE'] ?? $_s_cfgs['CFG_DEBUG'] ?? false),
            ],
            '©handle_exceptions' => (bool) ($_s_cfgs['CFG_HANDLE_EXCEPTIONS'] ?? false),

            '©locales' => (array) ($_s_cfgs['CFG_LOCALES'] ?? ($is_cfg_host ? ['en_US.UTF-8', 'C'] : [])),

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
                        Classes\Core\CliOpts::class,
                        Classes\Core\Template::class,
                        Classes\Core\Tokenizer::class,
                        Classes\Core\Base\Exception::class,
                    ],
                ],
            ],

            '©sub_namespace_map' => [
                // 'SCore' => [
                //     '©utils'   => '§',
                //     '©facades' => 's',
                // ],
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

            '©brand' => [ // NOTE: `short_` variations should not exceed 10 bytes each.

                '©name'    => (string) ($_s_cfgs['CFG_BRAND_NAME'] ?? $_s_cfgs['CFG_HOST'] ?? $host),
                '©acronym' => (string) ($_s_cfgs['CFG_BRAND_ACRONYM'] ?? 'APP'),

                '©text_domain' => (string) ($_s_cfgs['CFG_BRAND_TEXT_DOMAIN'] ?? $_s_cfgs['CFG_BRAND_SLUG'] ?? $_s_cfgs['CFG_SLUG'] ?? 'app'),

                '©slug' => (string) ($_s_cfgs['CFG_BRAND_SLUG'] ?? $_s_cfgs['CFG_SLUG'] ?? 'app'),
                '©var'  => (string) ($_s_cfgs['CFG_BRAND_VAR'] ?? $_s_cfgs['CFG_VAR'] ?? 'app'),

                '©short_slug' => (string) ($_s_cfgs['CFG_BRAND_SHORT_SLUG'] ?? $_s_cfgs['CFG_BRAND_SLUG'] ?? $_s_cfgs['CFG_SLUG'] ?? 'app'),
                '©short_var'  => (string) ($_s_cfgs['CFG_BRAND_SHORT_VAR'] ?? $_s_cfgs['CFG_BRAND_VAR'] ?? $_s_cfgs['CFG_VAR'] ?? 'app'),

                '©keywords'    => (string) ($_s_cfgs['CFG_BRAND_KEYWORDS'] ?? ''),
                '©description' => (string) ($_s_cfgs['CFG_BRAND_DESCRIPTION'] ?? ''),
                '©tagline'     => (string) ($_s_cfgs['CFG_BRAND_TAGLINE'] ?? ''),

                '©favicon'    => (string) ($_s_cfgs['CFG_BRAND_FAVICON'] ?? '/favicon.ico'),
                '©logo'       => (string) ($_s_cfgs['CFG_BRAND_LOGO'] ?? '/client-s/images/logo.png'),
                '©screenshot' => (string) ($_s_cfgs['CFG_BRAND_SCREENSHOT'] ?? '/client-s/images/screenshot.png'),
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
                '©base_paths' => [
                    '©app' => (string) ($_s_cfgs['CFG_HOST_BASE_PATH'] ?? ($is_cfg_host ? '' : '/src')),
                ],
                '©cdn_filter_enable' => (bool) ($_s_cfgs['CFG_CDN_FILTER_ENABLE'] ?? false),
                '©default_scheme'    => (string) ($_s_cfgs['CFG_DEFAULT_URL_SCHEME'] ?? 'https'),
                '©sig_key'           => (string) ($_s_cfgs['CFG_URL_SIG_KEY'] ?? ''),
            ],

            '©fs_paths' => [
                '©logs_dir'    => (string) ($_s_cfgs['CFG_LOGS_DIR'] ?? '/var/log/%%app_namespace_sha1%%'),
                '©cache_dir'   => (string) ($_s_cfgs['CFG_CACHE_DIR'] ?? '/tmp/%%app_namespace_sha1%%/cache'),
                '©errors_dir'  => (string) ($_s_cfgs['CFG_ERRORS_DIR'] ?? ($is_cfg_host ? '/bootstrap/src/html/errors' : '')),
                '©config_file' => (string) ($_s_cfgs['CFG_CONFIG_FILE'] ?? ''), // Empty by default, for improved performance.
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
                '©encryption_key' => (string) ($_s_cfgs['CFG_COOKIES_ENCRYPTION_KEY'] ?? ''),
            ],
            '©hash_ids' => [
                '©hash_key' => (string) ($_s_cfgs['CFG_HASH_IDS_HASH_KEY'] ?? ''),
            ],
            '©passwords' => [
                '©hash_key' => (string) ($_s_cfgs['CFG_PASSWORDS_HASH_KEY'] ?? ''),
            ],

            '©aws' => [
                '©access_key' => (string) ($_s_cfgs['CFG_AWS_ACCESS_KEY'] ?? ''),
                '©secret_key' => (string) ($_s_cfgs['CFG_AWS_SECRET_KEY'] ?? ''),
            ],
            '©embedly' => [
                '©api_key' => (string) ($_s_cfgs['CFG_EMBEDLY_API_KEY'] ?? ''),
            ],
            '©webpurify' => [
                '©api_key' => (string) ($_s_cfgs['CFG_WEBPURIFY_API_KEY'] ?? ''),
            ],
            '©bitly' => [
                '©api_key' => (string) ($_s_cfgs['CFG_BITLY_API_KEY'] ?? ''),
            ],
            '©mailchimp' => [
                '©list_id' => (string) ($_s_cfgs['CFG_MAILCHIMP_LIST_ID'] ?? ''),
                '©api_key' => (string) ($_s_cfgs['CFG_MAILCHIMP_API_KEY'] ?? ''),
            ],
            '©slack' => [ // @TODO Add Slack utils to core.
                '©api_client_id'     => (string) ($_s_cfgs['CFG_SLACK_API_CLIENT_ID'] ?? ''),
                '©api_client_secret' => (string) ($_s_cfgs['CFG_SLACK_API_CLIENT_SECRET'] ?? ''),
                '©api_access_token'  => (string) ($_s_cfgs['CFG_SLACK_API_ACCESS_TOKEN'] ?? ''),
                '©api_webhook_url'   => (string) ($_s_cfgs['CFG_SLACK_API_WEBHOOK_URL'] ?? ''),
            ],
        ];
        # Merge instance bases together now; forming a collective instance base.

        $instance_base = $this->App->mergeConfig($default_instance_base, $instance_base);

        # Merge a possible JSON configuration file also; via `include()` for improved performance.

        $config_file            = $instance['©fs_paths']['©config_file'] ?? $instance_base['©fs_paths']['©config_file'];
        $is_default_config_file = $config_file && $config_file === $default_instance_base['©fs_paths']['©config_file'];

        if ($config_file && (!$is_default_config_file || is_file($config_file))) {
            ob_start(); // Buffer contents of the JSON data.
            include $config_file; // Improved performance via OPcache.
            if (!is_array($config = json_decode(ob_get_clean(), true))) {
                throw new Exception(sprintf('Invalid config file: `%1$s`.', $config_file));
            }
            if (!empty($config['©core_app'])) {
                $config = (array) $config['©core_app'];
            } elseif (!empty($config['©app'])) {
                $config = (array) $config['©app'];
            }
            $config = $this->App->mergeConfig($instance_base, $config, true);
            $config = $this->App->mergeConfig($config, $instance);
        } else {
            $config = $this->App->mergeConfig($instance_base, $instance);
        }
        # Fill replacement codes and overload the config properties.

        $config = $this->App->fillConfigReplacementCodes($config);
        $config = (object) $config; // Force object properties.

        if (!$config->©debug['©enable']) { // Master switch off?
            $config->©debug['©edge']      = false;
            $config->©debug['©log']       = $config->©debug['©log_callback']       = false;
            $config->©debug['©er_enable'] = $config->©debug['©er_display'] = $config->©debug['©er_assertions'] = false;
        } elseif (!$config->©debug['©er_enable']) {
            $config->©debug['©er_display'] = $config->©debug['©er_assertions'] = false;
        }
        $this->overload($config, true); // Overload public/writable properties.
        // Public for performance gains & accessibility, but do NOT alter them at runtime.
    }
}
