<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function esc_html(...$args)
{
    return $GLOBALS[App::class]->Utils->Escape->html(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function unesc_html(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlEntities->decode(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_textarea(...$args)
{
    return $GLOBALS[App::class]->Utils->Escape->textarea(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_attr(...$args)
{
    return $GLOBALS[App::class]->Utils->Escape->attr(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_url(...$args)
{
    return $GLOBALS[App::class]->Utils->Escape->url(...$args);
}

/**
 * @since 160110 Adding shell arg escape.
 */
function esc_shell_arg(...$args)
{
    return $GLOBALS[App::class]->Utils->Escape->shell_arg(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_sql_name(...$args)
{
    return $GLOBALS[App::class]->Utils->Pdo->escName(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_sql_cols(...$args)
{
    return $GLOBALS[App::class]->Utils->Pdo->escColumns(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_sql_order(...$args)
{
    return $GLOBALS[App::class]->Utils->Pdo->escOrder(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_sql_order_bys(...$args)
{
    return $GLOBALS[App::class]->Utils->Pdo->escOrderBys(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_sql_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Pdo->escIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function esc_regex(...$args)
{
    return $GLOBALS[App::class]->Utils->RegexQuote->__invoke(...$args);
}
