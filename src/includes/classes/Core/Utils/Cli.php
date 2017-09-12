<?php
/**
 * CLI utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * CLI utilities.
 *
 * @since 150424 Initial release.
 */
class Cli extends Classes\Core\Base\Core
{
    /**
     * Running in a CLI?
     *
     * @since 150424 Initial release.
     *
     * @return bool True if running in a CLI.
     */
    public function is(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * CLI is an interactive terminal?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` CLI is an interactive terminal.
     */
    public function isInteractive(): bool
    {
        return !empty($_SERVER['TERM']) && $this->is();
    }
}
