<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function base64_url_safe_encode(...$args)
{
    return $GLOBALS[App::class]->Utils->Base64->urlSafeEncode(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function base64_url_safe_decode(...$args)
{
    return $GLOBALS[App::class]->Utils->Base64->urlSafeDecode(...$args);
}
