<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function set_scheme(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlScheme->set(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function app_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Url->toApp(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function app_core_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Url->toAppCore(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function cur_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Url->toCurrent(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function cur_core_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Url->toCurrentCore(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function cdn_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Cdn->url(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function cdn_s3_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Cdn->s3Url(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function cdn_filter(...$args)
{
    return $GLOBALS[App::class]->Utils->Cdn->filter(...$args);
}
