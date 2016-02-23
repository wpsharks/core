<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Url->isValid(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function strip_url_fragment(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlFragment->strip(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function normalize_url_amps(...$args)
{
    return $GLOBALS[App::class]->Utils->Url->normalizeAmps(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function remote_request(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlRemote->request(...$args);
}
