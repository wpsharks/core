<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Escapes
{
    /**
     * @since 151214 Adding functions.
     */
    public static function escHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->html(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function unescHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HtmlEntities->decode(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escTextarea(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->textarea(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escAttr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->attr(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->url(...$args);
    }

    /**
     * @since 160110 Adding shell arg escape.
     */
    public static function escShellArg(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->shellArg(...$args);
    }

    /**
     * @since 160422 SQL utils.
     */
    public static function quoteSql(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quote(...$args);
    }

    /**
     * @since 160422 SQL utils.
     */
    public static function quoteSqlIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quoteIn(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->escapeName(...$args);
    }

    /**
     * @since 160422 SQL utils.
     */
    public static function quoteSqlCols(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quoteColumns(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escSqlOrder(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->escapeOrder(...$args);
    }

    /**
     * @since 160422 SQL utils.
     */
    public static function quoteSqlOrderBys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quoteOrderBys(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function escRegex(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexEscape->__invoke(...$args);
    }
}
