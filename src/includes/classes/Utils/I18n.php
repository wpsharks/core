<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * i18n utilities.
 *
 * @since 160113 Initial release.
 */
class I18n extends Classes\AppBase
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
    public function /* @codingStandardsIgnoreStart */ __ /*@codingStandardsIgnoreEnd*/(string $string): string
    {
        if ($this->App->Config->i18n['text_domain']) {
            return $string;
        }
        return $string;
    }
}
