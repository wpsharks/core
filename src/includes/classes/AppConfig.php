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
    protected $¤ = [];

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

        # App class filesystem paths.

        $AppReflection             = new \ReflectionClass($App);
        $this->¤['app_class_file'] = $AppReflection->getFileName();
        $this->¤['app_class_dir']  = dirname($this->¤['app_class_file']);

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

            'email' => [
                'from_name'  => '',
                'from_email' => '',

                'reply_to_name'  => '',
                'reply_to_email' => '',

                'smtp_host'     => '',
                'smtp_port'     => 587,
                'smtp_secure'   => 'tls',
                'smtp_username' => '',
                'smtp_password' => '',
            ],

            'db_shards' => [
                'common' => [
                    'port'    => 3306,
                    'charset' => 'utf8mb4',

                    'username' => '',
                    'password' => '',

                    'ssl_enable' => true,
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
                            'host' => '127.0.0.1',
                            'name' => 'db0',
                        ],
                    ],
                ],
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

            'i18n' => [
                'locales'     => ['en_US.UTF-8', 'C'],
                'text_domain' => '', // Off by default.
            ],

            'fs_paths' => [
                'cache_dir'     => dirname($this->¤['app_class_dir'], 3).'/.~cache',
                'templates_dir' => dirname($this->¤['app_class_dir'], 1).'/templates',
                'config_file'   => dirname($this->¤['app_class_dir'], 3).'/assets/.~config.json',
            ],
            'cdn' => [
                'base_url' => 'https://cdn.websharks-inc.com',
            ],
        ];
        # Merge instance base w/ constructor instance.

        if (isset($instance['di']['default_rule']['new_instances'])) {
            $instance['di']['default_rule']['new_instances'] = array_merge(
                $instance_base['di']['default_rule']['new_instances'],
                $instance['di']['default_rule']['new_instances']
            ); // Merge; i.e., do not override base.
        }
        if (isset($instance['i18n']['locales'])) {
            $instance_base['i18n']['locales'] = $instance['i18n']['locales'];
        }
        if (isset($instance['db_shards']['dbs'])) {
            $instance_base['db_shards']['dbs'] = $instance['db_shards']['dbs'];
        }
        $instance = array_replace_recursive($instance_base, $instance);

        # Merge the configuration file also now.

        if (!$instance['fs_paths']['config_file'] || !is_file($instance['fs_paths']['config_file'])) {
            throw new Exception(sprintf('Missing config file: `%1$s`.', $instance['fs_paths']['config_file']));
        } elseif (!is_array($config = json_decode(file_get_contents($instance['fs_paths']['config_file']), true))) {
            throw new Exception(sprintf('Invalid config file: `%1$s`.', $instance['fs_paths']['config_file']));
        }
        unset($config['di'], $config['fs_paths']['config_file']); // Not allowed in config file.

        if (isset($config['i18n']['locales'])) {
            $instance['i18n']['locales'] = $config['i18n']['locales'];
        }
        if (isset($config['db_shards']['dbs'])) {
            $instance['db_shards']['dbs'] = $config['db_shards']['dbs'];
        }
        $config = array_replace_recursive($instance, $config);
        $config = $this->fillReplacementCodes($config);
        $this->overload((object) $config, true);

        # Initialize.

        $this->maybeDebug();
        $this->maybeSetLocales();
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
            $value = str_replace('%%app_class_dir%%', $this->¤['app_class_dir'], $value);
        }
        return $value;
    }

    /**
     * Maybe setup debugging.
     *
     * @since 15xxxx Initial release.
     */
    protected function maybeDebug()
    {
        if ($this->debug) {
            error_reporting(-1);
            ini_set('display_errors', 'yes');
            ini_set('zend.assertions', '1');
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
            setlocale(LC_ALL, $this->i18n['locales']);
        }
    }
}
