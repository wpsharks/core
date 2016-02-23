<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function contains_bad_words(...$args)
{
    return $GLOBALS[App::class]->Utils->WebPurify->check(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function slug_contains_bad_words(...$args)
{
    return $GLOBALS[App::class]->Utils->WebPurify->checkSlug(...$args);
}
