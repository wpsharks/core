<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL scheme utilities.
 *
 * @since 150424 Initial release.
 */
class UrlScheme extends Classes\AbsBase
{
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
    public function set(string $url, string $scheme = null): string
    {
        if (!isset($scheme)) {
            $scheme = $this->Utils->UrlCurrent->scheme();
        }
        if (mb_substr($url, 0, 2) === '//') {
            $url = 'http:'.$url;
        }
        if ($scheme === '//') {
            $url = preg_replace('/^\w+\:\/\//u', '//', $url);
        } elseif ($scheme === 'relative') {
            $url = preg_replace('/^\w+\:\/\/[^\/]*/u', '', $url);
        } else {
            $url = preg_replace('/^\w+\:\/\//u', $scheme.'://', $url);
        }
        return $url;
    }
}
