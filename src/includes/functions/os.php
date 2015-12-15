<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_unix(...$args)
{
    return $GLOBALS[App::class]->Utils->Os->isUnix(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_linux(...$args)
{
    return $GLOBALS[App::class]->Utils->Os->isLinux(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_mac(...$args)
{
    return $GLOBALS[App::class]->Utils->Os->isMac(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_windows(...$args)
{
    return $GLOBALS[App::class]->Utils->Os->isWindows(...$args);
}
