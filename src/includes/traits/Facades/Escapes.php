<?php
/**
 * Escapes.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Escapes.
 *
 * @since 160708
 */
trait Escapes
{
    /**
     * @since 160708 Quote utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::singleQuote()
     */
    public static function sQuote(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->singleQuote(...$args);
    }

    /**
     * @since 160708 Quote utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::doubleQuote()
     */
    public static function dQuote(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->doubleQuote(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::html()
     */
    public static function escHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->html(...$args);
    }

    /**
     * @since 170124.74961 HTML chars.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::htmlChars()
     */
    public static function escHtmlChars(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->htmlChars(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HtmlEntities::decode()
     */
    public static function unescHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HtmlEntities->decode(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::textarea()
     */
    public static function escTextarea(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->textarea(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::attr()
     */
    public static function escAttr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->attr(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::url()
     */
    public static function escUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->url(...$args);
    }

    /**
     * @since 160110 Adding shell arg escape.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Escape::shellArg()
     */
    public static function escShellArg(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Escape->shellArg(...$args);
    }

    /**
     * @since 160422 SQL utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sql::quote()
     */
    public static function quoteSql(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quote(...$args);
    }

    /**
     * @since 160422 SQL utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sql::quoteIn()
     */
    public static function quoteSqlIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quoteIn(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sql::escapeName()
     */
    public static function escSqlName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->escapeName(...$args);
    }

    /**
     * @since 160422 SQL utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sql::quoteColumns()
     */
    public static function quoteSqlCols(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quoteColumns(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sql::escapeOrder()
     */
    public static function escSqlOrder(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->escapeOrder(...$args);
    }

    /**
     * @since 160422 SQL utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sql::quoteOrderBys()
     */
    public static function quoteSqlOrderBys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sql->quoteOrderBys(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RegexEscape::__invoke()
     */
    public static function escRegex(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexEscape->__invoke(...$args);
    }

    /**
     * @since 170211.63148 Markdown enhancements.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RegexEscape::m0EscNoVws()
     */
    public static function regexM0EscNoVws(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexEscape->m0EscNoVws(...$args);
    }
}
