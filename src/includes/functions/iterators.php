<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function array_recursive_iterator(...$args)
{
    return $GLOBALS[App::class]->Utils->Iterators->arrayRecursive(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function dir_regex_recursive_iterator(...$args)
{
    return $GLOBALS[App::class]->Utils->Iterators->dirRecursiveRegex(...$args);
}
