<?php
/**
 * Url fragment utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Url fragment utilities.
 *
 * @since 150424 Initial release.
 */
class UrlFragment extends Classes\Core\Base\Core
{
    /**
     * URL without a #hash/fragment.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string URL without a #hash fragment.
     */
    public function strip(string $url_uri_qsl): string
    {
        if (mb_strpos($url_uri_qsl, '#') !== false) {
            $url_uri_qsl = mb_strstr($url_uri_qsl, '#', true);
        }
        return $url_uri_qsl;
    }
}
