<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * Spellcheck Utils.
 *
 * @since 15xxxx Adding password strength.
 */
class SpellCheck extends Classes\AbsBase
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
        if (is_null($dictionary = &$this->staticKey(__FUNCTION__, $flags))) {
            $dictionary = pspell_new('en', '', '', 'utf-8', $flags);
        }
        return pspell_check($dictionary, $word);
    }
}
