<?php
namespace WebSharks\Core\Traits;

/**
 * URL Parse Utilities.
 *
 * @since 150424 Initial release.
 */
trait UrlParseUtils
{
    /**
     * Parses a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string $url       Input URL to parse.
     * @param int    $component Optional component to retrieve.
     *
     * @return array|string|int|null Array, else string|int component value.
     */
    protected function parseUrl($url, $component = -1)
    {
        $url       = (string) $url;
        $component = (integer) $component;
        ${'//'}    = strpos($url, '//') === 0;

        if ($component > -1) {
            if (${'//'} && $component === PHP_URL_SCHEME) {
                return ($part = '//');
            }
            return ($part = parse_url($url, $component));
        } else {
            if (!is_array($parts = parse_url($url))) {
                return ($parts = array());
            }
            if (${'//'}) {
                $parts['scheme'] = '//';
            }
            return $parts;
        }
    }

    /**
     * Unparses a URL.
     *
     * @since 150424 Initial release.
     *
     * @param array $parts Input URL parts.
     *
     * @return string Unparsed URL in string format.
     */
    protected function unparseUrl(array $parts)
    {
        $scheme   = '';
        $host     = '';
        $port     = '';
        $user     = '';
        $pass     = '';
        $path     = '';
        $query    = '';
        $fragment = '';

        if (!empty($parts['scheme'])) {
            if ($parts['scheme'] === '//') {
                $scheme = $parts['scheme'];
            } else {
                $scheme = $parts['scheme'].'://';
            }
        }
        if (!empty($parts['host'])) {
            $host = $parts['host'];
        }
        if (!empty($parts['port'])) {
            $port = ':'.$parts['port'];
        }
        if (!empty($parts['user'])) {
            $user = $parts['user'];
        }
        if (!empty($parts['pass'])) {
            $pass = $parts['pass'];
        }
        if ($user || $pass) {
            $pass .= '@';
        }
        if (!empty($parts['path'])) {
            $path = '/'.ltrim($parts['path'], '/');
        }
        if (!empty($parts['query'])) {
            $query = '?'.$parts['query'];
        }
        if (!empty($parts['fragment'])) {
            $fragment = '#'.$parts['fragment'];
        }
        return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
    }
}
