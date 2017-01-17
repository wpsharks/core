<?php
/**
 * Paginator.
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
 * Paginator.
 *
 * @since 161006 Paginator.
 */
class Paginator extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 161006 Paginator.
     *
     * @param Classes\App $App  Instance of App.
     * @param array       $args Pagination args.
     */
    public function __construct(Classes\App $App, array $args = [])
    {
        parent::__construct($App);

        $default_args = [
            'page'       => 1,
            'per_page'   => 25,

            'found_rows' => 0,
            'range'      => 2,

            'page_url_callback'     => null,
            'page_replacement_code' => '%%page%%',
            'page_url'              => '#%%page%%',

            'page_class'               => 'item',
            'page_icon_class'          => 'item',
            'page_active_class'        => 'active item',
            'page_disabled_class'      => 'disabled item',
            'page_icon_disabled_class' => 'disabled item',
            'pages_class'              => 'ui mini pagination menu',

            'prev_page_text'  => __('Prev'),
            'prev_page_icon'  => '<i class="chevron left icon"></i>',
            'first_page_icon' => '<i class="angle double left icon"></i>',

            'last_page_icon'  => '<i class="angle double right icon"></i>',
            'next_page_icon'  => '<i class="chevron right icon"></i>',
            'next_page_text'  => __('Next'),

            'ellipsis_icon'  => '<i class="ellipsis horizontal icon"></i>',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        foreach ($args as $_key => &$_value) {
            if ($_key !== 'page_url_callback') {
                $_value = (string) $_value;
            }
        } // unset($_key, $_value); // Housekeeping.

        $args['page']       = max(1, (int) $args['page']);
        $args['per_page']   = max(1, (int) $args['per_page']);
        $args['found_rows'] = max(0, (int) $args['found_rows']);
        $args['range']      = max(1, (int) $args['range']);

        $this->overload((object) $args, true);
    }

    /**
     * Set found rows.
     *
     * @since 161006 Paginator.
     *
     * @param int $found_rows Found rows.
     */
    public function setFoundRows(int $found_rows)
    {
        $this->found_rows = max(0, $found_rows);
    }

    /**
     * Build pagination links.
     *
     * @since 161006 Paginator.
     *
     * @return string Pagination links.
     */
    public function links(): string
    {
        $first_page = 1; // Always `1` (first page).
        $last_page  = (int) ceil($this->found_rows / $this->per_page);

        // Note: `6` = Prev, [x], ..., ..., [x], Next.
        // Calculations below force a consistent menu width.
        // i.e., Always the same number of links in the menu.

        if ($last_page < ($this->range * 2) + 6) {
            $start_page = $first_page;
            $end_page   = $last_page;
        } elseif ($this->page < ($this->range * 2) + 1) {
            $start_page = $first_page;
            $end_page   = ($this->range * 2) + 3;
        } elseif ($this->page > $last_page - ($this->range * 2)) {
            $start_page = $last_page - ($this->range * 2) - 2;
            $end_page   = $last_page;
        } else {
            $start_page = $this->page - $this->range;
            $end_page   = $this->page + $this->range;
        }
        $start_page = max($first_page, $start_page);
        $end_page   = min($last_page, $end_page);

        $links = '<div class="'.$this->pages_class.'">';

        $links .= '<a class="-prev-page '.$this->c::escAttr($this->page === $first_page ? $this->page_icon_disabled_class : $this->page_icon_class).'"'.
                    ' href="'.$this->c::escUrl($this->page === $first_page ? '#' : $this->pageUrl($this->page - 1)).'">';
        $links .= $this->prev_page_icon.' '.$this->c::escHtml($this->prev_page_text);
        $links .= '</a>';

        if ($start_page > $first_page) {
            $links .= '<a class="-first-page '.$this->c::escAttr($this->page_class).'"'.
                        ' href="'.$this->c::escUrl($this->pageUrl($first_page)).'">';
            $links .= $this->first_page_icon.' '.$first_page;
            $links .= '</a>';

            $links .= '<span class="-ellipsis '.$this->c::escAttr($this->page_icon_disabled_class).'">';
            $links .= $this->ellipsis_icon;
            $links .= '</span>';
        }
        for ($_page = $start_page; $_page <= $end_page; ++$_page) {
            $links .= '<a class="-page '.$this->c::escAttr($_page === $this->page ? $this->page_active_class : $this->page_class).'"'.
                        ' href="'.$this->c::escUrl($_page === $this->page ? '#' : $this->pageUrl($_page)).'">';
            $links .= $_page;
            $links .= '</a>';
        }
        if ($end_page < $last_page) {
            $links .= '<span class="-ellipsis '.$this->c::escAttr($this->page_icon_disabled_class).'">';
            $links .= $this->ellipsis_icon;
            $links .= '</span>';

            $links .= '<a class="-last-page '.$this->c::escAttr($this->page_class).'"'.
                        ' href="'.$this->c::escUrl($this->pageUrl($last_page)).'">';
            $links .= $last_page.' '.$this->last_page_icon;
            $links .= '</a>';
        }
        $links .= '<a class="-next-page '.$this->c::escAttr($this->page === $last_page ? $this->page_icon_disabled_class : $this->page_icon_class).'"'.
                    ' href="'.$this->c::escUrl($this->page === $last_page ? '#' : $this->pageUrl($this->page + 1)).'">';
        $links .= $this->c::escHtml($this->next_page_text).' '.$this->next_page_icon;
        $links .= '</a>';

        $links .= '</div>';

        return $links;
    }

    /**
     * Get page URL.
     *
     * @since 170117 Paginator.
     *
     * @param int $page Page number.
     *
     * @return string Page URL.
     */
    protected function pageUrl(int $page): string
    {
        if ($this->page_url_callback && is_callable($this->page_url_callback)) {
            return str_replace($this->page_replacement_code, $page, (string) $this->page_url_callback($page));
        }
        return str_replace($this->page_replacement_code, $page, $this->page_url);
    }
}
