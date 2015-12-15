<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function email(...$args)
{
    return $GLOBALS[App::class]->Utils->Email->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_email(...$args)
{
    return $GLOBALS[App::class]->Utils->Email->isValid(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_role_based_email(...$args)
{
    return $GLOBALS[App::class]->Utils->Email->isRoleBased(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function parse_email_addresses(...$args)
{
    return $GLOBALS[App::class]->Utils->Email->parseAddresses(...$args);
}
