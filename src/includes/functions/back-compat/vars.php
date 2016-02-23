<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160220 Var utils.
 */
function is_var(...$args)
{
    return $GLOBALS[App::class]->Utils->Vars->isValid(...$args);
}

/**
 * @since 160220 Var utils.
 */
function var_to_name(...$args)
{
    return $GLOBALS[App::class]->Utils->Vars->toName(...$args);
}

/**
 * @since 160220 Var utils.
 */
function var_to_acronym(...$args)
{
    return $GLOBALS[App::class]->Utils->Vars->toAcronym(...$args);
}

/**
 * @since 160220 Var utils.
 */
function var_to_slug(...$args)
{
    return $GLOBALS[App::class]->Utils->Vars->toSlug(...$args);
}
