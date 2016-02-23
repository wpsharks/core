<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Gravatar utilities.
 *
 * @since 160114 Initial release.
 */
class Gravatar extends Classes\Core
{
    /**
     * Get a user gravatar.
     *
     * @since 160114 Initial release.
     *
     * @param string $email  Email address.
     * @param int    $size   A valid gravatar size.
     * @param string $scheme Specific scheme?
     *
     * @return string Gravatar URL.
     */
    public function url(string $email, int $size = 64, string $scheme = ''): string
    {
        $md5 = md5(mb_strtolower(c\mb_trim($email)));
        return c\set_scheme('https://www.gravatar.com/avatar/'.$md5.'?s='.$size);
    }
}
