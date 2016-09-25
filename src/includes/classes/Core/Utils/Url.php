<?php
/**
 * URL utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * URL utilities.
 *
 * @since 151121 URL utilities.
 */
class Url extends Classes\Core\Base\Core implements Interfaces\UrlConstants, Interfaces\HtmlConstants
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
        if (!($host = $this->App->Config->©urls['©hosts']['©app'])) {
            throw $this->c::issue('App host is empty.');
        }
        $uri = $uri ? $this->c::mbLTrim($uri, '/') : '';
        $uri = $uri ? '/'.$uri : ''; // Force leading slash.

        if ($uri && defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            $uri = preg_replace('/\.min\.js($|[?])/u', '.js${1}', $uri);
        } // This allows developers to debug uncompressed JS files.

        $base_path = $this->App->Config->©urls['©base_paths']['©app'];
        $base_path = $base_path && $uri ? $this->c::mbRTrim($base_path, '/') : $base_path;

        $url = $this->c::setScheme('//'.$host.$base_path.$uri, $scheme);

        if ($uri && $cdn_filter) {
            $url = $this->c::cdnFilter($url);
        }
        return $url;
    }

    /**
     * Build app parent URL.
     *
     * @since 160423 Parent utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL.
     */
    public function toAppParent(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        return $this->App->Parent ? $this->App->Parent->Utils->©Url->toApp($uri, $scheme, $cdn_filter) : $this->toApp($uri, $scheme, $cdn_filter);
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
        if ($this->App->Parent) { // Looking for the root core.
            return $this->App->Parent->Utils->©Url->toAppCore($uri, $scheme, $cdn_filter);
        }
        return $this->toApp($uri, $scheme, $cdn_filter);
    }

    /**
     * Build app WS core URL.
     *
     * @since 160925 URL utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL.
     */
    public function toAppWsCore(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        if ($this->App->Parent) { // Looking for the root core.
            return $this->App->Parent->Utils->©Url->toAppWsCore($uri, $scheme, $cdn_filter);
        }
        if (!$this->App->is_ws_core) {
            $uri = $uri ? $this->c::mbLTrim($uri, '/') : '';
            $uri = $uri ? '/'.$uri : ''; // Force leading slash.
            $uri = '/vendor/websharks/core/src'.$uri;
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
        if (!($host = $this->c::currentHost())) {
            throw $this->c::issue('Current host is empty.');
        }
        $uri = $uri ? $this->c::mbLTrim($uri, '/') : '';
        $uri = $uri ? '/'.$uri : ''; // Force leading slash.

        if ($uri && defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            $uri = preg_replace('/\.min\.js($|[?])/u', '.js${1}', $uri);
        } // This allows developers to debug uncompressed JS files.

        $base_path = $this->App->Config->©urls['©base_paths']['©app'];
        $base_path = $base_path && $uri ? $this->c::mbRTrim($base_path, '/') : $base_path;

        $url = $this->c::setScheme('//'.$host.$base_path.$uri, $scheme);

        if ($uri && $cdn_filter) {
            $url = $this->c::cdnFilter($url);
        }
        return $url;
    }

    /**
     * Build parent URL w/ current host.
     *
     * @since 160423 Parent utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL w/ current host.
     */
    public function toCurrentParent(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        return $this->App->Parent ? $this->App->Parent->Utils->©Url->toCurrent($uri, $scheme, $cdn_filter) : $this->toCurrent($uri, $scheme, $cdn_filter);
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
        if ($this->App->Parent) { // Looking for the root core.
            return $this->App->Parent->Utils->©Url->toCurrentCore($uri, $scheme, $cdn_filter);
        }
        return $this->toCurrent($uri, $scheme, $cdn_filter);
    }

    /**
     * Build current WS core URL.
     *
     * @since 160925 URL utilities.
     *
     * @param string $uri        URI to append.
     * @param string $scheme     Specific scheme?
     * @param bool   $cdn_filter CDN filter?
     *
     * @return string Output URL.
     */
    public function toCurrentWsCore(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        if ($this->App->Parent) { // Looking for the root core.
            return $this->App->Parent->Utils->©Url->toCurrentWsCore($uri, $scheme, $cdn_filter);
        }
        if (!$this->App->is_ws_core) {
            $uri = $uri ? $this->c::mbLTrim($uri, '/') : '';
            $uri = $uri ? '/'.$uri : ''; // Force leading slash.
            $uri = '/vendor/websharks/core/src'.$uri;
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
        if (($regex_amps = &$this->cacheKey(__FUNCTION__.'_regex_amps')) === null) {
            $regex_amps = implode('|', array_keys($this::HTML_AMPERSAND_ENTITIES));
        }
        return preg_replace('/(?:'.$regex_amps.')/uS', '&', $qs_url_uri);
    }
}
