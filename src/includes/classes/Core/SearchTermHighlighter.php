<?php
/**
 * Search term highligher.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Search term highligher.
 *
 * @since 161007 Highligher.
 */
class SearchTermHighlighter extends Classes\Core\Base\Core
{
    /**
     * Search query.
     *
     * @since 161007 Highligher.
     *
     * @var string
     */
    protected $q;

    /**
     * Search terms.
     *
     * @since 161007 Highligher.
     *
     * @var array
     */
    protected $terms;

    /**
     * Search terms.
     *
     * @since 161007 Highligher.
     *
     * @var string
     */
    protected $regex;

    /**
     * Highlight class.
     *
     * @since 161007 Highligher.
     *
     * @var string
     */
    protected $class;

    /**
     * Class constructor.
     *
     * @since 161007 Highligher.
     *
     * @param Classes\App $App  Instance of App.
     * @param string      $q    The search query.
     * @param array       $args Highligher args.
     */
    public function __construct(Classes\App $App, string $q, array $args = [])
    {
        parent::__construct($App);

        $default_args = [
            'class' => '-hst',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);
        $args = array_map('strval', $args);

        $this->q     = $q; // Copy of param.
        $this->terms = $this->parseTerms($this->q);
        $this->regex = $this->termsToRegex($this->terms);
        $this->class = $this->c::escAttr((string) $args['class']);
    }

    /**
     * Highlight.
     *
     * @since 161007 Highligher.
     *
     * @param string $q Search query.
     *
     * @return array Search terms.
     *
     * @see http://jas.xyz/2dkGVEj MySQL boolean syntax.
     */
    protected function parseTerms(string $q): array
    {
        if (!$q) { // Query is empty?
            return []; // No terms.
        }
        $terms = []; // Initialize.

        if (preg_match_all('/"(?<phrase>[^"]+)"/u', $q, $_m)) {
            $q = preg_replace('/"(?:[^"]+)"/u', '', $q);

            foreach ($_m['phrase'] as $_phrase) {
                $terms[] = $_phrase; // As a single term.
            } // unset($_m, $_phrase); // Housekeeping.
        } // Quotes are stripped BEFORE special chars below.

        $q = preg_replace('/\-\w+/u', '', $q);
        $q = preg_replace('/[~+](\w+)/u', '${1}', $q);
        $q = preg_replace('/[()*<>]+/u', '', $q);

        $terms        = array_merge($terms, preg_split('/\s+/', $q));
        $terms        = $this->c::remoteEmptys($this->c::mbTrim($terms));
        return $terms = array_unique($terms);
    }

    /**
     * Highlight.
     *
     * @since 161007 Highligher.
     *
     * @param array $terms Search terms.
     *
     * @return array Search terms.
     */
    protected function termsToRegex(array $terms): string
    {
        return $terms ? '/(?<term>'.implode('|', $this->c::escRegex($terms)).')/ui' : '';
    }

    /**
     * Highlight.
     *
     * @since 161007 Highligher.
     *
     * @param string $string String to highlight.
     *
     * @return string Highlighted string.
     */
    public function highlight(string $string): string
    {
        if (!$this->q || !$this->terms || !$this->regex) {
            return $string; // Nothing to do.
        }
        return $string = preg_replace_callback($this->regex, function ($m) {
            return '<i class="'.$this->class.'">'.$this->c::escHtml($m['term']).'</i>';
        }, $string);
    }
}
