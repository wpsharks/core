<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_utf8(...$args)
{
    return $GLOBALS[App::class]->Utils->Utf8->isValid(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_lcfirst(...$args)
{
    return $GLOBALS[App::class]->Utils->MbLcFirst->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_strcasecmp(...$args)
{
    return $GLOBALS[App::class]->Utils->StrCaseCmp->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_str_pad(...$args)
{
    return $GLOBALS[App::class]->Utils->StrPad->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_strrev(...$args)
{
    return $GLOBALS[App::class]->Utils->StrRev->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_str_split(...$args)
{
    return $GLOBALS[App::class]->Utils->StrSplit->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_substr_replace(...$args)
{
    return $GLOBALS[App::class]->Utils->SubstrReplace->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_trim(...$args)
{
    return $GLOBALS[App::class]->Utils->Trim->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_ltrim(...$args)
{
    return $GLOBALS[App::class]->Utils->Trim->l(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_rtrim(...$args)
{
    return $GLOBALS[App::class]->Utils->Trim->r(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_ucfirst(...$args)
{
    return $GLOBALS[App::class]->Utils->UcFirst->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function mb_ucwords(...$args)
{
    return $GLOBALS[App::class]->Utils->UcWords->__invoke(...$args);
}
