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
class UrlScheme extends Classes\AbsBase implements Interfaces\UrlConstants
{
    /**
     * Set the scheme for a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string $url    Absolute URL that includes a scheme (or a `//` scheme).
     * @param string $scheme ``|`default`, `current`, `//`, `relative`, `https`, `http`, or another.
     *
     * @return string $url URL w/ a specific scheme.
     */
    public function set(string $url, string $scheme = 'default'): string
    {
        if (!$scheme || $scheme === 'default') {
            $scheme = $this->App->Config->urls['default_scheme'];
        } elseif ($scheme === 'current') {
            $scheme = $this->Utils->UrlCurrent->scheme();
        }
        if (!$scheme) {
            throw new Exception('Empty scheme.');
        }
        if (mb_substr($url, 0, 2) === '//') {
            $url = 'foobar:'.$url;
        }
        if ($scheme === '//') {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', '//', $url);
        } elseif ($scheme === 'relative') {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'[^\/]*/u', '', $url);
        } else {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', $scheme.'://', $url);
        }
        return $url;
    }
}
