<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function fname_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->firstIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function lname_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->lastIn(...$args);
}

/**
 * @since 160220 Acronym utils.
 */
function name_to_acronym(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->toAcronym(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function name_to_slug(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->toSlug(...$args);
}

/**
 * @since 160220 Var utils.
 */
function name_to_var(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->toVar(...$args);
}
