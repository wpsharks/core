<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Cli
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isCli(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cli->is(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isCliInteractive(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cli->isInteractive(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function readStdin(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->in(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function writeStdout(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->out(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function writeStdoutHr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->outHr(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function writeStderr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->err(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function writeStderrHr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliStream->errHr(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function cliOpenUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©CliOs->openUrl(...$args);
    }
}
