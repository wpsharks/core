<?php
/**
 * Output.
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
 * Output.
 *
 * @since 160622
 */
trait Output
{
    /**
     * @since 160622 Adding file output prep.
     */
    public static function prepareForFileOutput(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->filePrep(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function gzipOff(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->gzipOff(...$args);
    }

    /**
     * @since 160622 Adding session write/close.
     */
    public static function sessionWriteClose(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->sessionWriteClose(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function obEndCleanAll(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->buffersEndClean(...$args);
    }
}
