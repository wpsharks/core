<?php
/**
 * YAML.
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
 * YAML.
 *
 * @since 151214
 */
trait Yaml
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function parseYaml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Yaml->parse(...$args);
    }
}
