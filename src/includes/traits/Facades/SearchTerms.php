<?php
/**
 * Replacements.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Search terms.
 *
 * @since 161007
 */
trait SearchTerms
{
    /**
     * @since 161007 Search terms.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\SearchTerms::getHighlighter()
     */
    public static function getSearchTermHighlighter(...$args)
    {
        return $GLOBALS[static::class]->Utils->©SearchTerms->getHighlighter(...$args);
    }
}
