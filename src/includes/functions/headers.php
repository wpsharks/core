<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function status_header(...$args)
{
    return $GLOBALS[App::class]->Utils->Headers->sendStatus(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function parse_headers(...$args)
{
    return $GLOBALS[App::class]->Utils->Headers->parse(...$args);
}
