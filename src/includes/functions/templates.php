<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function get_template(...$args)
{
    return $GLOBALS[App::class]->Utils->Template->get(...$args);
}
