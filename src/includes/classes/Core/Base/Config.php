<?php
/**
 * Config.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
     */
    public function __construct(Classes\App $App, array $instance_base = [], array $instance = [])
    {
        parent::__construct($App);

        # Server CFGs (if applicable).

        $use_server_cfgs = (bool) ($instance['©use_server_cfgs']
            ?? $instance_base['©use_server_cfgs'] ?? !empty($_SERVER['CFG_HOST']));
        $_ = $use_server_cfgs && !empty($_SERVER['CFG_HOST']) ? $_SERVER : [];

        # Default host names; needed below as fallbacks.

        $host      = $_['CFG_HOST'] ?? $_SERVER['HTTP_HOST'] ?? mb_strtolower(php_uname('n'));
        $root_host = $_['CFG_ROOT_HOST'] ?? implode('.', array_slice(explode('.', $host), -2));

        # Default instance base (i.e., default config).

        $default_instance_base = [
            '©use_server_cfgs' => false,

            '©debug' => [
                '©enable' => (bool) ($_['CFG_DEBUG'] ?? false),
                '©edge'   => (bool) ($_['CFG_DEBUG_EDGE'] ?? false),

                '©log'          => (bool) ($_['CFG_DEBUG_LOG'] ?? $_['CFG_DEBUG'] ?? false),
                '©log_callback' => false, // In case log entries should be reviewed in other ways.

                '©er_enable'     => (bool) ($_['CFG_DEBUG_ER_ENABLE'] ?? $_['CFG_DEBUG'] ?? false),
                '©er_display'    => (bool) ($_['CFG_DEBUG_ER_DISPLAY'] ?? $_['CFG_DEBUG_ER_ENABLE'] ?? $_['CFG_DEBUG'] ?? false),
                '©er_assertions' => (bool) ($_['CFG_DEBUG_ER_ASSERTIONS'] ?? $_['CFG_DEBUG_ER_ENABLE'] ?? $_['CFG_DEBUG'] ?? false),
            ],
            '©handle_throwables' => (bool) ($_['CFG_HANDLE_THROWABLES'] ?? false),

            '©locales' => (array) ($_['CFG_LOCALES'] ?? ($_ ? ['en_US.UTF-8', 'C'] : [])),

            '©contacts' => [
                '©admin' => [
                    '©gender'       => (string) ($_['CFG_ADMIN_GENDER'] ?? 'male'),
                    '©username'     => (string) ($_['CFG_ADMIN_USERNAME'] ?? 'admin'),
                    '©name'         => (string) ($_['CFG_ADMIN_NAME'] ?? $_['CFG_ADMIN_USERNAME'] ?? 'Admin'),
                    '©email'        => (string) ($_['CFG_ADMIN_EMAIL'] ?? $_['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$root_host),
                    '©public_email' => (string) ($_['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$root_host),
                ],
            ],

            '©di' => [
                '©default_rule' => [
                    'new_instances' => [
                        Classes\Core\Error::class,
                        Classes\Core\Route::class,
                        Classes\Core\CliOpts::class,
                        Classes\Core\Template::class,
                        Classes\Core\Tokenizer::class,
                        Classes\Core\Paginator::class,
                        Classes\Core\SearchTermHighlighter::class,
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
                    (string) ($_['CFG_MYSQL_DB_HOST'] ?? '127.0.0.1') => [
                        '©port'    => (int) ($_['CFG_MYSQL_DB_PORT'] ?? 3306),
                        '©charset' => (string) ($_['CFG_MYSQL_DB_CHARSET'] ?? 'utf8mb4'),
                        '©collate' => (string) ($_['CFG_MYSQL_DB_COLLATE'] ?? 'utf8mb4_unicode_ci'),

                        '©username' => (string) ($_['CFG_MYSQL_DB_USERNAME'] ?? 'client'),
                        '©password' => (string) ($_['CFG_MYSQL_DB_PASSWORD'] ?? ''),

                        '©ssl_enable' => (bool) ($_['CFG_MYSQL_SSL_ENABLE'] ?? false),
                        '©ssl_key'    => (string) ($_['CFG_MYSQL_SSL_KEY'] ?? ''),
                        '©ssl_crt'    => (string) ($_['CFG_MYSQL_SSL_CRT'] ?? ''),
                        '©ssl_ca'     => (string) ($_['CFG_MYSQL_SSL_CA'] ?? ''),
                        '©ssl_cipher' => (string) ($_['CFG_MYSQL_SSL_CIPHER'] ?? ''),
                    ],
                ],
                '©shards' => [
                    [
                        '©range' => [
                            '©from' => 0,
                            '©to'   => 65535,
                        ],
                        '©properties' => [
                            '©host' => (string) ($_['CFG_MYSQL_DB_HOST'] ?? '127.0.0.1'),
                            '©name' => (string) ($_['CFG_MYSQL_DB_NAME'] ?? 'db0'),
                        ],
                    ],
                ],
            ],

            '©brand' => [ // `short_` variations should not exceed 10 bytes.
                '©acronym' => (string) ($_['CFG_BRAND_ACRONYM'] ?? 'APP'),
                '©name'    => (string) ($_['CFG_BRAND_NAME'] ?? $_['CFG_HOST'] ?? $host),

                '©author' => [ // Used for `<meta name="author" />`, personal sites, etc.
                    '©gender'       => (string) ($_['CFG_BRAND_AUTHOR_GENDER'] ?? $_['CFG_ADMIN_GENDER'] ?? 'male'),
                    '©username'     => (string) ($_['CFG_BRAND_AUTHOR_USERNAME'] ?? $_['CFG_ADMIN_USERNAME'] ?? 'admin'),
                    '©name'         => (string) ($_['CFG_BRAND_AUTHOR_NAME'] ?? $_['CFG_ADMIN_NAME'] ?? 'Admin'),
                    '©email'        => (string) ($_['CFG_BRAND_AUTHOR_EMAIL'] ?? $_['CFG_ADMIN_EMAIL'] ?? $_['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$root_host),
                    '©public_email' => (string) ($_['CFG_BRAND_AUTHOR_PUBLIC_EMAIL'] ?? $_['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'admin@'.$root_host),
                ],

                '©slug' => (string) ($_['CFG_BRAND_SLUG'] ?? $_['CFG_SLUG'] ?? 'app'),
                '©var'  => (string) ($_['CFG_BRAND_VAR'] ?? $_['CFG_VAR'] ?? 'app'),

                '©short_slug' => (string) ($_['CFG_BRAND_SHORT_SLUG'] ?? $_['CFG_BRAND_SLUG'] ?? $_['CFG_SLUG'] ?? 'app'),
                '©short_var'  => (string) ($_['CFG_BRAND_SHORT_VAR'] ?? $_['CFG_BRAND_VAR'] ?? $_['CFG_VAR'] ?? 'app'),

                '©text_domain' => (string) ($_['CFG_BRAND_TEXT_DOMAIN'] ?? $_['CFG_BRAND_SLUG'] ?? $_['CFG_SLUG'] ?? 'app'),

                '©keywords'    => (array) ($_['CFG_BRAND_KEYWORDS'] ?? []),
                '©description' => (string) ($_['CFG_BRAND_DESCRIPTION'] ?? ''),
                '©tagline'     => (string) ($_['CFG_BRAND_TAGLINE'] ?? ''),

                '©favicon' => (string) ($_['CFG_BRAND_FAVICON'] ?? '/favicon.ico'),
                '©logo'    => (string) ($_['CFG_BRAND_LOGO'] ?? '/client-s/images/logo.png'),
                '©image'   => (string) ($_['CFG_BRAND_IMAGE'] ?? '/client-s/images/image.png'),

                '©favicons' => [
                    '©dir'          => (string) ($_['CFG_BRAND_FAVICONS_DIR'] ?? '/client-s/images/favicons'),
                    '©theme_color'  => (string) ($_['CFG_BRAND_FAVICONS_THEME_COLOR'] ?? '#ffffff'),
                    '©pinned_color' => (string) ($_['CFG_BRAND_FAVICONS_PINNED_ACTIVE_COLOR'] ?? '#2b5797'),
                ],
            ],

            '©urls' => [
                '©hosts' => [ // Contains `:port` when necessary.
                    '©app' => (string) ($_['CFG_HOST'] ?? $host),
                    '©cdn' => (string) ($_['CFG_CDN_HOST'] ?? 'cdn.'.$root_host),

                    '©roots' => [ // Expected to contain `:port` when necessary.
                        '©app' => (string) ($_['CFG_ROOT_HOST'] ?? $root_host),
                        '©cdn' => (string) ($_['CFG_CDN_ROOT_HOST'] ?? $root_host),
                    ],
                ],
                '©base_paths' => [
                    '©app' => (string) ($_['CFG_HOST_BASE_PATH'] ?? ($_ ? '/' : '/src/')),
                    '©cdn' => (string) ($_['CFG_CDN_HOST_BASE_PATH'] ?? '/'),
                ],
                '©cdn_filter_enable' => (bool) ($_['CFG_CDN_FILTER_ENABLE'] ?? false),
                '©default_scheme'    => (string) ($_['CFG_DEFAULT_URL_SCHEME'] ?? 'https'),
                '©sig_key'           => (string) ($_['CFG_URL_SIG_KEY'] ?? $_['CFG_ENCRYPTION_KEY'] ?? ''),
            ],

            '©fs_paths' => [
                '©logs_dir'      => (string) ($_['CFG_LOGS_DIR'] ?? '/var/log/app/%%app_slug%%'),
                '©cache_dir'     => (string) ($_['CFG_CACHE_DIR'] ?? '/tmp/app/%%app_slug%%/cache'),
                '©sris_dir'      => (string) ($_['CFG_SRIS_DIR'] ?? '%%app_base_dir%%/src/client-s'),
                '©routes_dir'    => (string) ($_['CFG_ROUTES_DIR'] ?? '%%app_base_dir%%/src/includes/routes'),
                '©templates_dir' => (string) ($_['CFG_TEMPLATES_DIR'] ?? '%%app_base_dir%%/src/includes/templates'),
                '©errors_dir'    => (string) ($_['CFG_ERRORS_DIR'] ?? ($_ ? '/bootstrap/src/html/errors' : '')),
            ],
            '©fs_permissions' => [
                '©transient_dirs' => (int) ($_['CFG_TRANSIENT_DIR_PERMISSIONS'] ?? 02775),
                // `octdec(02775)` = 1533 as an integer.
            ],

            '©memcache' => [
                // NOTE: A `null` value indicates that auto-detection should be used (default).
                '©enabled'   => isset($_['CFG_MEMCACHE_ENABLED']) ? (bool) $_['CFG_MEMCACHE_ENABLED'] : null,
                '©namespace' => (string) ($_['CFG_MEMCACHE_NAMESPACE'] ?? $_['CFG_BRAND_SHORT_VAR'] ?? 'app'),
                '©servers'   => [
                    [
                        '©host'   => (string) ($_['CFG_MEMCACHE_HOST'] ?? '127.0.0.1'),
                        '©port'   => (int) ($_['CFG_MEMCACHE_PORT'] ?? 11211),
                        '©weight' => (int) ($_['CFG_MEMCACHE_WEIGHT'] ?? 0),
                    ],
                ],
            ],

            '©email' => [
                '©from_name'  => (string) ($_['CFG_EMAIL_FROM_NAME'] ?? $_['CFG_BRAND_NAME'] ?? $_['CFG_HOST'] ?? 'App'),
                '©from_email' => (string) ($_['CFG_EMAIL_FROM_EMAIL'] ?? $_['CFG_ADMIN_PUBLIC_EMAIL'] ?? 'app@'.$root_host),

                '©reply_to_name'  => (string) ($_['CFG_EMAIL_REPLY_TO_NAME'] ?? ''),
                '©reply_to_email' => (string) ($_['CFG_EMAIL_REPLY_TO_EMAIL'] ?? ''),

                '©smtp_host'   => (string) ($_['CFG_EMAIL_SMTP_HOST'] ?? '127.0.0.1'),
                '©smtp_port'   => (int) ($_['CFG_EMAIL_SMTP_PORT'] ?? 25),
                '©smtp_secure' => (string) ($_['CFG_EMAIL_SMTP_SECURE'] ?? ''),

                '©smtp_username' => (string) ($_['CFG_EMAIL_SMTP_USERNAME'] ?? ''),
                '©smtp_password' => (string) ($_['CFG_EMAIL_SMTP_PASSWORD'] ?? ''),
            ],

            '©encryption' => [
                '©key' => (string) ($_['CFG_ENCRYPTION_KEY'] ?? ''),
            ],
            '©cookies' => [
                '©encryption_key' => (string) ($_['CFG_COOKIES_ENCRYPTION_KEY'] ?? $_['CFG_ENCRYPTION_KEY'] ?? ''),
            ],
            '©hash_ids' => [
                '©hash_key' => (string) ($_['CFG_HASH_IDS_HASH_KEY'] ?? $_['CFG_ENCRYPTION_KEY'] ?? ''),
            ],
            '©passwords' => [
                '©hash_key' => (string) ($_['CFG_PASSWORDS_HASH_KEY'] ?? $_['CFG_ENCRYPTION_KEY'] ?? ''),
            ],

            '©aws' => [
                '©region'     => (string) ($_['CFG_AWS_REGION'] ?? 'us-east-1'),
                '©access_key' => (string) ($_['CFG_AWS_ACCESS_KEY'] ?? ''),
                '©secret_key' => (string) ($_['CFG_AWS_SECRET_KEY'] ?? ''),

                '©s3_bucket'  => (string) ($_['CFG_AWS_S3_BUCKET'] ?? ''),
                '©s3_version' => (string) ($_['CFG_AWS_S3_VERSION'] ?? '2006-03-01'),
            ],
            '©embedly' => [
                '©api_key' => (string) ($_['CFG_EMBEDLY_API_KEY'] ?? ''),
            ],
            '©webpurify' => [
                '©api_key' => (string) ($_['CFG_WEBPURIFY_API_KEY'] ?? ''),
            ],
            '©bitly' => [
                '©api_key' => (string) ($_['CFG_BITLY_API_KEY'] ?? ''),
            ],
            '©recaptcha' => [
                '©site_key'   => (string) ($_['CFG_RECAPTCHA_SITE_KEY'] ?? ''),
                '©secret_key' => (string) ($_['CFG_RECAPTCHA_SECRET_KEY'] ?? ''),
            ],
            '©mailchimp' => [
                '©list_id' => (string) ($_['CFG_MAILCHIMP_LIST_ID'] ?? ''),
                '©api_key' => (string) ($_['CFG_MAILCHIMP_API_KEY'] ?? ''),
            ],
            '©slack' => [
                '©api_client_id'     => (string) ($_['CFG_SLACK_API_CLIENT_ID'] ?? ''),
                '©api_client_secret' => (string) ($_['CFG_SLACK_API_CLIENT_SECRET'] ?? ''),
                '©api_access_token'  => (string) ($_['CFG_SLACK_API_ACCESS_TOKEN'] ?? ''),
                '©api_webhook_url'   => (string) ($_['CFG_SLACK_API_WEBHOOK_URL'] ?? ''),
            ],
            '©github' => [
                '©api_client_id'     => (string) ($_['CFG_GITHUB_API_CLIENT_ID'] ?? ''),
                '©api_client_secret' => (string) ($_['CFG_GITHUB_API_CLIENT_SECRET'] ?? ''),
                '©api_access_token'  => (string) ($_['CFG_GITHUB_API_ACCESS_TOKEN'] ?? ''),
            ],
            '©zenhub' => [
                '©api_client_id'     => (string) ($_['CFG_ZENHUB_API_CLIENT_ID'] ?? ''),
                '©api_client_secret' => (string) ($_['CFG_ZENHUB_API_CLIENT_SECRET'] ?? ''),
                '©api_access_token'  => (string) ($_['CFG_ZENHUB_API_ACCESS_TOKEN'] ?? ''),
            ],
        ];
        # Merge `$instance_base` param into `$default_instance_base`.

        $instance_base = $this->App->mergeConfig($default_instance_base, $instance_base);

        # Merge everything together & convert to object properties now.

        $config = $this->App->mergeConfig($instance_base, $instance);
        $config = $this->App->fillConfigReplacementCodes($config);
        $config = (object) $config; // Config properties.

        # Adjust a few values associated w/ master switches.

        if (!$config->©debug['©enable']) {
            $config->©debug['©edge']      = false;
            $config->©debug['©log']       = $config->©debug['©log_callback']       = false;
            $config->©debug['©er_enable'] = $config->©debug['©er_display'] = $config->©debug['©er_assertions'] = false;
        } elseif (!$config->©debug['©er_enable']) {
            $config->©debug['©er_display'] = $config->©debug['©er_assertions'] = false;
        }
        # Overload configuration properties.

        $this->overload($config, true); // Overload public/writable properties.
    }
}
