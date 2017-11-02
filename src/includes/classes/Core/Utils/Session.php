<?php
/**
 * Session utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Session utils.
 *
 * @since 17xxxx
 */
class Session extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 17xxxx Session utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if ($this->c::isCli()) {
            throw $this->c::issue('Not possible in CLI mode.');
        }
    }

    /**
     * Session active?
     *
     * @since 17xxxx Session utils.
     *
     * @return bool True if active.
     */
    public function isActive(): bool
    {
        return session_status() === \PHP_SESSION_ACTIVE;
    }

    /**
     * Start session.
     *
     * @since 17xxxx Session utils.
     *
     * @param string $name    Session name.
     * @param array  $options Session options.
     */
    public function start(string $name = '', array $options = [])
    {
        if (session_status() !== \PHP_SESSION_NONE) {
            throw $this->c::issue('Expecting inactive session.');
        } // Expecting inactive so we can start below.

        if ($name) {
            session_name($name);
        }
        $options += [ // Defaults.
            'use_strict_mode'        => true,
            'use_trans_sid'          => false,

            'sid_length'             => 64,
            'sid_bits_per_character' => 6,

            'use_cookies'            => true,
            'use_only_cookies'       => true,
            'cookie_lifetime'        => 0,
            'cookie_path'            => '/',
            'cookie_secure'          => true,
            'cookie_httponly'        => true,

            'gc_maxlifetime'         => 86400,
            'cache_limiter'          => 'nocache',

            'read_and_close' => false,
        ];
        if (!session_start($options)) {
            throw $this->c::issue('Unable to start session.');
        }
    }
}
