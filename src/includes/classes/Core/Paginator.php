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
            'show_pages' => 15,

            'page_url'    => '#%%page%%',
            'pages_class' => 'ui basic buttons',

            'page_class'          => 'ui button',
            'page_active_class'   => 'ui primary button',
            'page_disabled_class' => 'ui disabled button',

            'page_icon_class'          => 'ui icon button',
            'page_icon_disabled_class' => 'ui disabled icon button',

            'prev_page_icon' => '<i class="left arrow icon"></i>',
            'next_page_icon' => '<i class="right arrow icon"></i>',
            'ellipsis_icon'  => '<i class="ellipsis horizontal icon"></i>',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $args               = array_map('strval', $args);
        $args['page']       = max(1, (int) $args['page']);
        $args['per_page']   = max(1, (int) $args['per_page']);
        $args['found_rows'] = max(0, (int) $args['found_rows']);
        $args['show_pages'] = max(2, (int) $args['show_pages']);

        $this->overload((object) $args, true);
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
        $last_page  = ceil($this->found_rows / $this->per_page);

        $start_page = $this->page - $this->show_pages > 0 ? $this->page - $this->show_pages : 1;
        $end_page   = $this->page + $this->show_pages < $last_page ? $this->page + $this->show_pages : $last_page;

        $links = '<div class="'.$this->pages_class.'">';

        $links .= '<a class="'.$this->c::escAttr($this->page === $first_page ? $this->page_icon_disabled_class : $this->page_icon_class).'"'.
                    ' href="'.$this->c::escUrl($this->page === $first_page ? '#' : str_replace('%%page%%', $this->page - 1, $this->page_url)).'"'.
                    ' style="'.($this->page === $first_page ? 'pointer-events:none;' : '').'">';
        $links .= $this->prev_page_icon;
        $links .= '</a>';

        if ($start_page > $first_page) {
            $links .= '<a class="'.$this->c::escAttr($this->page_class).'"'.
                        ' href="'.$this->c::escUrl(str_replace('%%page%%', $first_page, $this->page_url)).'">';
            $links .= $first_page;
            $links .= '</a>';

            $links .= '<a class="'.$this->c::escAttr($this->page_icon_disabled_class).'"'.
                      ' href="#" style="pointer-events:none;">';
            $links .= $this->ellipsis_icon;
            $links .= '</a>';
        }
        for ($_page = $start_page; $_page <= $end_page; ++$_page) {
            $links .= '<a class="'.$this->c::escAttr($_page === $this->page ? $this->page_active_class : $this->page_class).'"'.
                        ' href="'.$this->c::escUrl($_page === $this->page ? '#' : str_replace('%%page%%', $_page, $this->page_url)).'"'.
                        ' style="'.($_page === $this->page ? 'pointer-events:none;' : '').'">';
            $links .= $_page;
            $links .= '</a>';
        }
        if ($end_page < $last_page) {
            $links .= '<a class="'.$this->c::escAttr($this->page_icon_disabled_class).'"'.
                      ' href="#" style="pointer-events:none;">';
            $links .= $this->ellipsis_icon;
            $links .= '</a>';

            $links .= '<a class="'.$this->c::escAttr($this->page_class).'"'.
                        ' href="'.$this->c::escUrl(str_replace('%%page%%', $last_page, $this->page_url)).'">';
            $links .= $last_page;
            $links .= '</a>';
        }
        $links .= '<a class="'.$this->c::escAttr($this->page === $last_page ? $this->page_icon_disabled_class : $this->page_icon_class).'"'.
                    ' href="'.$this->c::escUrl($this->page === $last_page ? '#' : str_replace('%%page%%', $this->page + 1, $this->page_url)).'"'.
                    ' style="'.($this->page === $last_page ? 'pointer-events:none;' : '').'">';
        $links .= $this->next_page_icon;
        $links .= '</a>';

        $links .= '</div>';

        return $links;
    }
}
