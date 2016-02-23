<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_version(...$args)
{
    return $GLOBALS[App::class]->Utils->Version->isValid(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_dev_version(...$args)
{
    return $GLOBALS[App::class]->Utils->Version->isValidDev(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_stable_version(...$args)
{
    return $GLOBALS[App::class]->Utils->Version->isValidStable(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_ws_version(...$args)
{
    return $GLOBALS[App::class]->Utils->WsVersion->isValid(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_ws_dev_version(...$args)
{
    return $GLOBALS[App::class]->Utils->WsVersion->isValidDev(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_ws_stable_version(...$args)
{
    return $GLOBALS[App::class]->Utils->WsVersion->isValidStable(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function ws_version_date(...$args)
{
    return $GLOBALS[App::class]->Utils->WsVersion->date(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function ws_version_time(...$args)
{
    return $GLOBALS[App::class]->Utils->WsVersion->time(...$args);
}
