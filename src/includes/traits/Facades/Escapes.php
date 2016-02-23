<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Escapes
{
    /**
     * @since 151214 Adding functions.
     */
    public static function escHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->Escape->html(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function unescHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlEntities->decode(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escTextarea(...$args)
    {
        return $GLOBALS[static::class]->Utils->Escape->textarea(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escAttr(...$args)
    {
        return $GLOBALS[static::class]->Utils->Escape->attr(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->Escape->url(...$args);
    }

    /**
     * @since 160110 Adding shell arg escape.
     */
    public static function escShellArg(...$args)
    {
        return $GLOBALS[static::class]->Utils->Escape->shellArg(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlName(...$args)
    {
        return $GLOBALS[static::class]->Utils->Pdo->escName(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlCols(...$args)
    {
        return $GLOBALS[static::class]->Utils->Pdo->escColumns(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlOrder(...$args)
    {
        return $GLOBALS[static::class]->Utils->Pdo->escOrder(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlOrderBys(...$args)
    {
        return $GLOBALS[static::class]->Utils->Pdo->escOrderBys(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->Pdo->escIn(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escRegex(...$args)
    {
        return $GLOBALS[static::class]->Utils->RegexQuote->__invoke(...$args);
    }
}
