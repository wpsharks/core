<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function file_ext(...$args)
{
    return $GLOBALS[App::class]->Utils->FileExt->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function file_size_abbr(...$args)
{
    return $GLOBALS[App::class]->Utils->FileSize->abbr(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function bytes_to_abbr(...$args)
{
    return $GLOBALS[App::class]->Utils->FileSize->bytesAbbr(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function abbr_to_bytes(...$args)
{
    return $GLOBALS[App::class]->Utils->FileSize->abbrBytes(...$args);
}
