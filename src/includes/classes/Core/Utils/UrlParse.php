<?php
/**
 * URL parse utilities.
 *
 * @author @jaswsinc
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
     * @since 16xxxx Adding `uri_no_fragment` key.
     * @since 16xxxx Adding `$include_extra_parts` param.
     *
     * @param string $url_uri_qsl         Input URL, URI, or query string w/ a leading `?`.
     * @param int    $component           Optional component to retrieve a value for.
     * @param bool   $include_extra_parts N/A when `$component` is > `-1`.
     *
     * @return array|string|int|null Array, else `string|int|null` component value.
     *
     * Additional parts (i.e., `$include_extra_parts`) includes:
     *
     * - `full` Full original URL before parsing.
     * - `canonical` Full original URL minus `?query#fragment` parts.
     *
     * - `uri` Unparsed URL w/ only the `path`, `query`, and `fragment` parts.
     *    If the `path` part is not empty it will always start w/ a leading `/`.
     * - `uri_no_fragment` Unparsed URL w/ only the `path` and `query` parts.
     */
    public function __invoke(string $url_uri_qsl, int $component = -1, bool $include_extra_parts = true)
    {
        // Note: We must allow for `0` here.
        // It will parse as `[path => '0']`, which is valid.

        ${'//'} = mb_strpos($url_uri_qsl, '//') === 0;

        if ($component > -1) {
            if (${'//'} && $component === PHP_URL_SCHEME) {
                return $part = '//'; // Supports cross-protocol scheme.
            } else {
                return $part = parse_url($url_uri_qsl, $component);
            }
        } else { // Parse all parts.
            $parts = parse_url($url_uri_qsl);

            if (!is_array($parts)) {
                return $parts = [];
            }
            if (${'//'}) {
                $parts['scheme'] = '//';
            }
            if ($include_extra_parts) {
                $parts['full']            = $url_uri_qsl;
                $parts['canonical']       = preg_split('/[?#]/u', $url_uri_qsl, 2)[0];

                $parts['uri']             = $this->un(array_intersect_key($parts, ['path' => null, 'query' => null, 'fragment' => null]));
                $parts['uri_no_fragment'] = $parts['uri'] ? preg_split('/#/u', $parts['uri'], 2)[0] : $parts['uri'];
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
    public function un(array $parts): string
    {
        $scheme   = '';
        $host     = $port     = '';
        $user     = $pass     = '';
        $path     = $query     = $fragment = '';

        $parts = array_map('strval', $parts);

        if (isset($parts['scheme'][0])) {
            if ($parts['scheme'] === '//') {
                $scheme = $parts['scheme'];
            } else {
                $scheme = $parts['scheme'].'://';
            }
        } // Supports cross-protocol scheme.

        if (isset($parts['host'][0])) {
            $host = $parts['host'];
        }
        if (isset($parts['port'][0])) {
            $port = ':'.$parts['port'];
        }
        if (isset($parts['user'][0])) {
            $user = $parts['user'];
        }
        if (isset($parts['pass'][0])) {
            $pass = ':'.$parts['pass'];
        }
        if (isset($user[0]) || isset($pass[0])) {
            $pass .= '@'; // `user@`, `:pass@`, or `user:pass@`.
        }
        if (isset($parts['path'][0])) {
            $path = '/'.$this->c::mbLTrim($parts['path'], '/');
        }
        if (isset($parts['query'][0])) {
            $query = '?'.$parts['query'];
        }
        if (isset($parts['fragment'][0])) {
            $fragment = '#'.$parts['fragment'];
        }
        return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
    }
}
