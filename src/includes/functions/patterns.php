<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function regex_frag(...$args)
{
    return $GLOBALS[App::class]->Utils->RegexFrag->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function regex_pattern_in(...$args)
{
    return $GLOBALS[App::class]->Utils->RegexPattern->in(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function regex_patterns_match(...$args)
{
    return $GLOBALS[App::class]->Utils->RegexPatterns->match(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function wd_regex(...$args)
{
    return $GLOBALS[App::class]->Utils->WdRegex->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function wd_regex_frag(...$args)
{
    return $GLOBALS[App::class]->Utils->WdRegex->frag(...$args);
}
