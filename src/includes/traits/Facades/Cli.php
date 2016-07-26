<?php
/**
 * CLI.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * CLI.
 *
 * @since 151214
 */
trait Cli
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Cli::is()
     */
    public static function isCli(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cli->is(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Cli::isInteractive()
     */
    public static function isCliInteractive(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cli->isInteractive(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\CliStream::in()
     */
    public static function readStdin(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->in(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\CliStream::out()
     */
    public static function writeStdout(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->out(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\CliStream::outHr()
     */
    public static function writeStdoutHr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->outHr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\CliStream::err()
     */
    public static function writeStderr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->err(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\CliStream::errHr()
     */
    public static function writeStderrHr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->errHr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\CliOs::openUrl()
     */
    public static function cliOpenUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliOs->openUrl(...$args);
    }
}
