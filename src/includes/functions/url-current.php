<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_ssl(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->isSsl(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_localhost(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->isLocalhost(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_url(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_scheme(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->scheme(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_host(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->host(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_root_host(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->rootHost(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_uri(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->uri(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_path(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->path(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function current_path_info(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlCurrent->pathInfo(...$args);
}
