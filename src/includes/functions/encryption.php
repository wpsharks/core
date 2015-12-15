<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function random_key(...$args)
{
    return $GLOBALS[App::class]->Utils->RandomKey->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function password_strength(...$args)
{
    return $GLOBALS[App::class]->Utils->PwStrength->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function password_sha256(...$args)
{
    return $GLOBALS[App::class]->Utils->Password->sha256(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function encrypt(...$args)
{
    return $GLOBALS[App::class]->Utils->Rijndael256->encrypt(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function decrypt(...$args)
{
    return $GLOBALS[App::class]->Utils->Rijndael256->decrypt(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function sha256_keyed_hash(...$args)
{
    return $GLOBALS[App::class]->Utils->Sha256->keyedHash(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function hash_ids(...$args)
{
    return $GLOBALS[App::class]->Utils->HashIds->encode(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function decode_hash_id(...$args)
{
    return $GLOBALS[App::class]->Utils->HashIds->decode(...$args);
}
