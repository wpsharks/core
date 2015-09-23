<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * URL hash utilities.
 *
 * @since 150424 Initial release.
 */
class UrlHash extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * URL without a #hash/fragment.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string URL without a #hash/fragment.
     */
    public function strip(string $url_uri_qsl): string
    {
        if (strpos($url_uri_qsl, '#') !== false) {
            $url_uri_qsl = strstr($url_uri_qsl, '#', true);
        }
        return $url_uri_qsl;
    }
}
