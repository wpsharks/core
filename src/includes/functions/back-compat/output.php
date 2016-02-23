<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function gzip_off(...$args)
{
    return $GLOBALS[App::class]->Utils->Output->gzipOff(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function ob_end_clean_all(...$args)
{
    return $GLOBALS[App::class]->Utils->Output->buffersEndClean(...$args);
}
