<?php
namespace WebSharks\Core\Traits;

/**
 * URL hash utilities.
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
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string URL without a #hash/fragment.
     */
    protected function urlHashStrip($url_uri_qsl)
    {
        $url_uri_qsl = (string) $url_uri_qsl;
        if (strpos($url_uri_qsl, '#') !== false) {
            $url_uri_qsl = strstr($url_uri_qsl, '#', true);
        }
        return $url_uri_qsl;
    }
}
