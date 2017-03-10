<?php
/**
 * XML.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * XML.
 *
 * @since 160829.74007
 */
trait Xml
{
    /**
     * @since 160829.74007 XML conversion utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Array2Xml::__invoke()
     */
    public static function arrayToXml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Array2Xml->__invoke(...$args);
    }

    /**
     * @since 160829.74007 XML conversion utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Array2Xml::toHtml()
     */
    public static function arrayToHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Array2Xml->toHtml(...$args);
    }
}
