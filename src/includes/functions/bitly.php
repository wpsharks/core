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
