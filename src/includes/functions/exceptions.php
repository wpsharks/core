<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function setup_exception_handler(...$args)
{
    return $GLOBALS[App::class]->Utils->Exceptions->handle(...$args);
}
