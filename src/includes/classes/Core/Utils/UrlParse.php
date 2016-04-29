<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL parse utilities.
 *
 * @since 150424 Initial release.
 */
class UrlParse extends Classes\Core\Base\Core
{
    /**
     * Parses a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     * @param int    $component   Optional component to retrieve.
     *
     * @return array|string|int|null Array, else `string|int|null` component value.
     */
    public function __invoke(string $url_uri_qsl, int $component = -1)
    {
        ${'//'} = mb_strpos($url_uri_qsl, '//') === 0;

        if ($component > -1) {
            if (${'//'} && $component === PHP_URL_SCHEME) {
                return $part = '//';
            }
            return $part = parse_url($url_uri_qsl, $component);
        } else {
            if (!is_array($parts = parse_url($url_uri_qsl))) {
                return $parts = [];
            }
            if (${'//'}) {
                $parts['scheme'] = '//';
            }
            $parts['full']      = $url_uri_qsl;
            $parts['canonical'] = preg_split('/[?#]/u', $url_uri_qsl, 2)[0];
            $parts['uri']       = $this->un(array_intersect_key($parts, ['path' => null, 'query' => null, 'fragment' => null]));

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
    public function un(array $parts): string
    {
        $parts = array_map('strval', $parts);

        $scheme   = '';
        $host     = $port     = '';
        $user     = $pass     = '';
        $path     = $query     = '';
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
            $path = '/'.$this->c::mbLTrim($parts['path'], '/');
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
