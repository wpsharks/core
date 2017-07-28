<?php
/**
 * SHA-1 utils.
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
 * SHA-1 utils.
 *
 * @since 17xxxx SHA-1 utils.
 */
trait Sha1
{
    /**
     * @since 17xxxx SHA-1 utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha1::is()
     */
    public static function isSha1(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha1->is(...$args);
    }
}
