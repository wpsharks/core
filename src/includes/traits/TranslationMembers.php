<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes\Exception;

/**
 * Translation members.
 *
 * @since 150424 Initial release.
 */
trait TranslationMembers
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
    protected function __(string $string): string
    {
        return $string;
    }
}
