<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL utilities.
 *
 * @since 151121 URL utilities.
 */
class Url extends Classes\Core implements Interfaces\UrlConstants, Interfaces\HtmlConstants
{
    /**
     * Build app URL.
     *
     * @since 151121 URL utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL.
     */
    public function toApp(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        if (!($host = $this->App->Config->urls['hosts']['app'])) {
            throw new Exception('App host is empty.');
        }
        $uri = $uri ? c\mb_ltrim($uri, '/') : '';
        $url = c\set_scheme('//'.$host.'/'.$uri, $scheme);

        if ($uri && $cdn_filter) {
            $url = c\cdn_filter($url);
        }
        return $url;
    }

    /**
     * Build app core URL.
     *
     * @since 151121 URL utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL.
     */
    public function toAppCore(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        if ($this->App->core_is_vendor) {
            $uri = $uri ? c\mb_ltrim($uri, '/') : '';
            $uri = '/vendor/websharks/core/src/'.$uri;
        }
        return $this->toApp($uri, $scheme, $cdn_filter);
    }

    /**
     * Build URL w/ current host.
     *
     * @since 151121 URL utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL w/ current host.
     */
    public function toCurrent(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        $host = c\current_host();
        $uri  = $uri ? c\mb_ltrim($uri, '/') : '';
        $url  = c\set_scheme('//'.$host.'/'.$uri, $scheme);

        if ($uri && $cdn_filter) {
            $url = c\cdn_filter($url);
        }
        return $url;
    }

    /**
     * Build current core URL.
     *
     * @since 151121 URL utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL.
     */
    public function toCurrentCore(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        if ($this->App->core_is_vendor) {
            $uri = $uri ? c\mb_ltrim($uri, '/') : '';
            $uri = '/vendor/websharks/core/src/'.$uri;
        }
        return $this->toCurrent($uri, $scheme, $cdn_filter);
    }

    /**
     * Valid URL?
     *
     * @since 151121 URL utilities.
     *
     * @param string $url URL to check.
     *
     * @return bool True if URL is valid.
     */
    public function isValid(string $url): bool
    {
        return (bool) preg_match($this::URL_REGEX_VALID, $url);
    }

    /**
     * Normalize ampersands.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (w/ or w/o a leading `?`), a URL, or URI.
     *
     * @return string The `$qs_url_uri` w/ normalized ampersands.
     */
    public function normalizeAmps(string $qs_url_uri): string
    {
        if (!$qs_url_uri) {
            return $qs_url_uri; // Possible `0`.
        }
        if (is_null($regex_amps = &$this->cacheKey(__FUNCTION__.'_regex_amps'))) {
            $regex_amps = implode('|', array_keys($this::HTML_AMPERSAND_ENTITIES));
        }
        return preg_replace('/(?:'.$regex_amps.')/uS', '&', $qs_url_uri);
    }
}
