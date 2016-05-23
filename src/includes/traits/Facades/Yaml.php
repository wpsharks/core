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

trait Yaml
{
    /**
     * @since 151214 Adding functions.
     */
    public static function parseYaml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Yaml->parse(...$args);
    }
}
