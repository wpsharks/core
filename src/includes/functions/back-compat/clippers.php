<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function clip(...$args)
{
    return $GLOBALS[App::class]->Utils->Clip->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mid_clip(...$args)
{
    return $GLOBALS[App::class]->Utils->MidClip->__invoke(...$args);
}
