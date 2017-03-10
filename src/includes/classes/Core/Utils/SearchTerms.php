<?php
/**
 * Search terms.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Search terms.
 *
 * @since 161006 Search terms.
 */
class SearchTerms extends Classes\Core\Base\Core
{
    /**
     * Get search highlighter.
     *
     * @since 161006 Search terms.
     *
     * @param string $q    Input search query.
     * @param array  $args Additional arguments.
     *
     * @return Classes\Core\SearchTermHighlighter Instance.
     */
    public function getHighlighter(string $q, array $args = []): Classes\Core\SearchTermHighlighter
    {
        return $this->App->Di->get(Classes\Core\SearchTermHighlighter::class, compact('q', 'args'));
    }
}
