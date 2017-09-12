<?php
/**
 * PDO.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * PDO.
 *
 * @since 160422
 */
trait Pdo
{
    /**
     * @since 160422 SQL utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Pdo::quote()
     */
    public static function pdoQuote(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Pdo->quote(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Pdo::get()
     */
    public static function pdoGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Pdo->get(...$args);
    }

    /**
     * @since 160422 SQL utils.
     * @see Classes\Core\Utils\Pdo::$current
     */
    public static function currentPdo()
    {
        return $GLOBALS[static::class]->Utils->©Pdo->current;
    }
}
