<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * UTF-8 Utils.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class Utf8 extends AbsBase
{
    const BOM = "\xEF\xBB\xBF";

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
     * Is valid UTF-8?
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param string $string Input string to test.
     *
     * @return bool True if is valid UTF-8.
     */
    public function isValid(string $string): bool
    {
        if (!isset($string[0])) {
            return true;
        }
        return (bool) preg_match('/^./us', $string);
    }
}
