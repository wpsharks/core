<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

/**
 * Quick i18n members.
 *
 * @since 150424 Initial release.
 */
trait QuickI18nMembers
{
    /**
     * Translate a string.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $string String to translate.
     *
     * @return string Translated string.
     */
    protected function /* @codingStandardsIgnoreStart */ __ /*@codingStandardsIgnoreEnd*/(string $string): string
    {
        if ($this->App->Config->i18n['text_domain']) {
            return $string;
        }
        return $string;
    }
}
