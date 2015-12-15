<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function tmp_dir(...$args)
{
    return $GLOBALS[App::class]->Utils->DirTmp->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function normalize_dir_path(...$args)
{
    return $GLOBALS[App::class]->Utils->DirPath->normalize(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function delete_dir(...$args)
{
    return $GLOBALS[App::class]->Utils->DirDelete->__invoke(...$args);
}
