<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function strip_url_query(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->strip(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function parse_url_query(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->parse(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function build_url_query(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->build(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function add_url_query_args(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->addArgs(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function remove_url_query_args(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->removeArgs(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function add_url_query_sig(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->addSha256Sig(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function remove_url_query_sig(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->removeSha256Sig(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function url_query_sig_ok(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlQuery->sha256SigOk(...$args);
}
