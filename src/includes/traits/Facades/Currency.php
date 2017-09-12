<?php
/**
 * Currency utils.
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
 * Currency utils.
 *
 * @since 170824.30708 Currency utils.
 */
trait Currency
{
    /**
     * @since 170824.30708 Currency utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Currency::symbol()
     */
    public static function currencySymbol(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Currency->symbol(...$args);
    }
}
