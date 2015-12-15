<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function parse_yaml(...$args)
{
    return $GLOBALS[App::class]->Utils->Yaml->parse(...$args);
}
