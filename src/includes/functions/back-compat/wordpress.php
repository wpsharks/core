<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160219 WP utils.
 */
function is_wordpress(...$args)
{
    return $GLOBALS[App::class]->Utils->WordPress->is(...$args);
}
