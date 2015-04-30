<?php
namespace WebSharks\Core\Traits;

/**
 * URL Hash Utilities.
 *
 * @since 150424 Initial release.
 */
trait UrlHashUtils
{
    /**
     * URL without a #hash/fragment.
     *
     * @since 150424 Initial release.
     *
     * @param string $url The input URL to work from.
     *
     * @return string URL without a #hash/fragment.
     */
    protected function stripUrlHash($url)
    {
        $url = (string) $url;
        if (strpos($url, '#') !== false) {
            $url = strstr($url, '#', true);
        }
        return $url;
    }
}
