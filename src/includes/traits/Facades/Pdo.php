<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Pdo
{
    /**
     * @since 160422 SQL utils.
     */
    public static function pdoQuote(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Pdo->quote(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function pdoGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Pdo->get(...$args);
    }

    /**
     * @since 160422 SQL utils.
     */
    public static function currentPdo()
    {
        return $GLOBALS[static::class]->Utils->©Pdo->current;
    }
}
