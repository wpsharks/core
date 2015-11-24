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

        $default_instance = [
            'debug'             => false,
            'debug_test_config' => false,

            'di_default_rule' => [
                'new_instances' => [
                    self::class,
                    AppDi::class,
                    AppUtils::class,
                    CliOpts::class,
                    Exception::class,
                ],
            ],
            'locales'     => ['en_US.UTF-8', 'C'],
            'text_domain' => '', // Off by default.

            'email_from_name'  => '',
            'email_from_email' => '',

            'email_reply_to_name'  => '',
            'email_reply_to_email' => '',

            'email_smtp_host'     => '',
            'email_smtp_port'     => 587,
            'email_smtp_secure'   => 'tls',
            'email_smtp_username' => '',
            'email_smtp_password' => '',

            'assets_dir' => dirname(__FILE__, 4).'/assets',

            'db_shards' => (object) [
                'common' => (object) [
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
                    (object) [
                        'range' => (object) [
                            'from' => 0,
                            'to'   => 65535,
                        ],
                        'properties' => (object) [
                            'host' => '127.0.0.1',
                            'name' => 'db0',
                        ],
                    ],
                ],
            ],
        ];
        if (isset($instance['di_default_rule']['new_instances'])) {
            $di_new_instances         = &$instance['di_default_rule']['new_instances'];
            $default_di_new_instances = &$default_instance['di_default_rule']['new_instances'];
            $di_new_instances         = array_unique(array_merge($default_di_new_instances, $di_new_instances));
        }
        $instance = array_merge($default_instance, $instance); // Merge w/ defaults.

        if ($instance['debug'] && $instance['debug_test_config']) {
            $file = $instance['assets_dir'].'/config-test.json';
        } else {
            $file = $instance['assets_dir'].'/config.json';
        }
        if (!is_file($file)) { // Config. file must exist (always).
            throw new Exception(sprintf('Missing config file: `%1$s`.', $file));
        } elseif (!is_array($config = json_decode(file_get_contents($file), true))) {
            throw new Exception(sprintf('Invalid config file: `%1$s`.', $file));
        }
        // Do not allow these instance-only keys to be overridden by the config. file.
        unset($config['debug'], $config['debug_test_config'], $config['assets_dir'], $config['di_default_rule']);
        $this->overload((object) array_merge($instance, $config), true); // Config takes precedence.

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
