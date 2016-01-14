<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160102 Adding bitly.
 */
function bitly_shorten(...$args)
{
    return $GLOBALS[App::class]->Utils->Bitly->shorten(...$args);
}

/**
 * @since 160114 Bitly link history.
 */
function bitly_link_history(...$args)
{
    return $GLOBALS[App::class]->Utils->Bitly->linkHistory(...$args);
}
