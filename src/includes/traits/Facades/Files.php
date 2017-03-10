<?php
/**
 * Files.
 *
 * @author @jaswrks
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
 * Files.
 *
 * @since 151214
 */
trait Files
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileExt::__invoke()
     */
    public static function fileExt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileExt->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::abbr()
     */
    public static function fileSizeAbbr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->abbr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::bytesAbbr()
     */
    public static function bytesToAbbr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->bytesAbbr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileSize::abbrBytes()
     */
    public static function abbrToBytes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->abbrBytes(...$args);
    }

    /**
     * @since 160926 Upload utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\FileUpload::errorReason()
     */
    public static function fileUploadErrorReason(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileUpload->errorReason(...$args);
    }
}
