<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160114 Gravatars.
 */
function gravatar_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Gravatar->url(...$args);
}
