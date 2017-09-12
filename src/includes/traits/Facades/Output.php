<?php
/**
 * Output.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

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
 * Output.
 *
 * @since 160622
 */
trait Output
{
    /**
     * @since 170824.30708 Early close of request.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::closeRequestEarly()
     */
    public static function closeRequestEarly(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->closeRequestEarly(...$args);
    }

    /**
     * @since 170824.30708 Shorter alias.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::prepFile()
     */
    public static function prepFileOutput(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->prepFile(...$args);
    }

    /**
     * @since 160622 Adding file output prep.
     * @deprecated 170824.30708 Use shorter `prepFileOutput()`.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::prepFile()
     */
    public static function prepareForFileOutput(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->prepFile(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::gzipOff()
     */
    public static function gzipOff(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->gzipOff(...$args);
    }

    /**
     * @since 160622 Adding session write/close.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::sessionWriteClose()
     */
    public static function sessionWriteClose(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->sessionWriteClose(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::buffersEndClean()
     */
    public static function obEndCleanAll(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->buffersEndClean(...$args);
    }

    /**
     * @since 170824.30708 Buffer end/flush.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Output::buffersEndFlush()
     */
    public static function obEndFlushAll(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->buffersEndFlush(...$args);
    }
}
