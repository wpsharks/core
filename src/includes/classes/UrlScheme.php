<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * URL scheme utilities.
 *
 * @since 150424 Initial release.
 */
class UrlScheme extends AbsBase
{
    protected $UrlCurrent;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        UrlCurrent $UrlCurrent
    ) {
        parent::__construct();

        $this->UrlCurrent = $UrlCurrent;
    }

    /**
     * Set the scheme for a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string      $url    Absolute URL that includes a scheme (or a `//` scheme).
     * @param null|string $scheme Optional; `//`, `relative`, `https`, or `http`.
     *
     * @return string $url URL w/ a specific scheme.
     */
    public function set($url, $scheme = null)
    {
        $url = (string) $url;
        if (!isset($scheme)) {
            $scheme = $this->UrlCurrent->scheme();
        }
        $scheme = (string) $scheme;

        if (substr($url, 0, 2) === '//') {
            $url = 'http:'.$url;
        }
        if ($scheme === '//') {
            $url = preg_replace('/^\w+\:\/\//', '//', $url);
        } elseif ($scheme === 'relative') {
            $url = preg_replace('/^\w+\:\/\/[^\/]*/', '', $url);
        } else {
            $url = preg_replace('/^\w+\:\/\//', $scheme.'://', $url);
        }
        return $url;
    }
}
