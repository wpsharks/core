<?php
/**
 * PHP.
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
 * PHP.
 *
 * @since 160505
 */
trait Php
{
    /**
     * @since 160505 PHP eval utils.
     */
    public static function phpEval(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpEval->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function canCallFunc(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpHas->callableFunc(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function memoryLimit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memory->limit(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function stripPhpTags(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpStrip->tags(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function uploadSizeLimit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UploadSize->limit(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function maxExecTime(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ExecTime->max(...$args);
    }

    /**
     * @since 160709 Userland constants.
     */
    public static function isValidFuncName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpUserland->isValidFunctionName(...$args);
    }
}
