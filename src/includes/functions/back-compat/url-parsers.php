<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function parse_url(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlParse->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function unparse_url(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlParse->un(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function parse_url_host(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlHost->parse(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function unparse_url_host(...$args)
{
    return $GLOBALS[App::class]->Utils->UrlHost->unParse(...$args);
}
