<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

/**
 * Quick escape members.
 *
 * @since 150424 Initial release.
 */
trait QuickEscMembers
{
    /**
     * Escape URL in `attr=""`.
     *
     * @since 151122 Escape members.
     *
     * @param string $string Input string.
     *
     * @return string Escaped output string.
     */
    protected function escUrl(string $string): string
    {
        return $this->Utils->HtmlEntities->esc($string);
    }

    /**
     * Escape `attr=""` (other).
     *
     * @since 151122 Escape members.
     *
     * @param string $string Input string.
     *
     * @return string Escaped output string.
     */
    protected function escAttr(string $string): string
    {
        return $this->Utils->HtmlEntities->esc($string);
    }

    /**
     * Escape `<textarea>` value.
     *
     * @since 151122 Escape members.
     *
     * @param string $string Input string.
     *
     * @return string Escaped output string.
     */
    protected function escTextarea(string $string): string
    {
        return $this->Utils->HtmlEntities->esc($string);
    }

    /**
     * Escape HTML markup (other).
     *
     * @since 151122 Escape members.
     *
     * @param string $string Input string.
     *
     * @return string Escaped output string.
     */
    protected function escHtml(string $string): string
    {
        return $this->Utils->HtmlEntities->esc($string);
    }
}
