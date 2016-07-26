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
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\PhpEval::__invoke()
     */
    public static function phpEval(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpEval->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\PhpHas::callableFunc()
     */
    public static function canCallFunc(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpHas->callableFunc(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Memory::limit()
     */
    public static function memoryLimit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memory->limit(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\PhpStrip::tags()
     */
    public static function stripPhpTags(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpStrip->tags(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UploadSize::limit()
     */
    public static function uploadSizeLimit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UploadSize->limit(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ExecTime::max()
     */
    public static function maxExecTime(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ExecTime->max(...$args);
    }

    /**
     * @since 160709 Userland constants.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\PhpUserland::isValidFunctionName()
     */
    public static function isValidFuncName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PhpUserland->isValidFunctionName(...$args);
    }
}
