<?php
/**
 * Gravatar utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Gravatar utilities.
 *
 * @since 160114 Initial release.
 */
class Gravatar extends Classes\Core\Base\Core
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
        $md5 = md5(mb_strtolower($this->c::mbTrim($email)));
        return $this->c::setScheme('https://www.gravatar.com/avatar/'.$md5.'?s='.$size, $scheme);
    }
}
