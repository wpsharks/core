<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL utilities.
 *
 * @since 151121 URL utilities.
 */
class Url extends Classes\AbsBase implements Interfaces\UrlConstants
{
    /**
     * Valid URL?
     *
     * @since 151121 URL utilities.
     *
     * @param string $url URL to check.
     *
     * @return bool True if URL is valid.
     */
    public function isValid(string $url): bool
    {
        return (bool) preg_match($this::URL_REGEX_VALID, $url);
    }
}
