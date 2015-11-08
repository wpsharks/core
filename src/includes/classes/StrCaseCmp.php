<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Multibyte `strcasecmp()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class StrCaseCmp extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Enhancing multibyte support.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Multibyte `strcasecmp()`.
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param string $string1 Input string.
     * @param string $string2 Input string.
     *
     * @return int See: <http://php.net/manual/en/function.strcmp.php>
     */
    public function __invoke(string $string1, string $string2): int
    {
        return strcmp(mb_strtolower($string1), mb_strtolower($string2));
    }
}
