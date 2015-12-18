<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Spellcheck Utils.
 *
 * @since 15xxxx Adding password strength.
 */
class SpellCheck extends Classes\AppBase
{
    /**
     * Checks spelling.
     *
     * @since 15xxxx Adding password strength.
     *
     * @param string $word  Input word to check.
     * @param int    $flags Dictionary flags.
     *
     * @return bool True if spelled correctly.
     */
    public function __invoke(string $word, int $flags = PSPELL_NORMAL): bool
    {
        if (is_null($dictionary = &$this->cacheKey(__FUNCTION__, $flags))) {
            $dictionary = pspell_new('en', '', '', 'utf-8', $flags);
        }
        return pspell_check($dictionary, $word);
    }
}
