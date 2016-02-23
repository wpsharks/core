<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Yaml
{
    /**
     * @since 151214 Adding functions.
     */
    public static function parseYaml(...$args)
    {
        return $GLOBALS[static::class]->Utils->Yaml->parse(...$args);
    }
}
