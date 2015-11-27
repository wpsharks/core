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
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $instance Instance properties.
     */
    public function __construct(array $instance = [])
    {
        parent::__construct();

        # Instance base.

        $instance_base = [
            'debug' => false,

            'di_default_rule' => [
                'new_instances' => [
                    self::class,
                    AppDi::class,
                    AppUtils::class,
                    CliOpts::class,
                    Exception::class,
                    Template::class,
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

            'locales'     => ['en_US.UTF-8', 'C'],
            'text_domain' => '', // Off by default.

            'assets_dir'    => dirname(__FILE__, 4).'/assets',
            'cache_dir'     => dirname(__FILE__, 4).'/.~cache',
            'templates_dir' => dirname(__FILE__, 2).'/templates',
            'config_file'   => dirname(__FILE__, 4).'/assets/.~config.json',
        ];
        # Merge instance base w/ constructor instance.

        if (isset($instance['di_default_rule']['new_instances'])) {
            $instance['di_default_rule']['new_instances'] = array_unique(array_merge(
                $instance_base['di_default_rule']['new_instances'],
                $instance['di_default_rule']['new_instances']
            )); // Merge; i.e., do not override base.
        }
        if (isset($instance['locales'])) {
            $instance_base['locales'] = $instance['locales'];
        }
        if (isset($instance['db_shards']['dbs'])) {
            $instance_base['db_shards']['dbs'] = $instance['db_shards']['dbs'];
        }
        $instance = array_replace_recursive($instance_base, $instance);

        # Merge the configuration file also now.

        if (!is_file($instance['config_file'])) {
            throw new Exception(sprintf('Missing config file: `%1$s`.', $instance['config_file']));
        } elseif (!is_array($config = json_decode(file_get_contents($instance['config_file']), true))) {
            throw new Exception(sprintf('Invalid config file: `%1$s`.', $instance['config_file']));
        }
        unset($config['config_file']);
        unset($config['di_default_rule']);

        if (isset($config['locales'])) {
            $instance['locales'] = $config['locales'];
        }
        if (isset($config['db_shards']['dbs'])) {
            $instance['db_shards']['dbs'] = $config['db_shards']['dbs'];
        }
        $config = (object) array_replace_recursive($instance, $config);

        # Overload the final config value.

        $this->overload($config, true);

        # Initialize.

        $this->maybeDebug();
        $this->maybeSetLocales();
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
        if ($this->locales) {
            setlocale(LC_ALL, $this->locales);
        }
    }
}
