<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function markdown(...$args)
{
    return $GLOBALS[App::class]->Utils->Markdown->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function strip_markdown(...$args)
{
    return $GLOBALS[App::class]->Utils->Markdown->strip(...$args);
}
