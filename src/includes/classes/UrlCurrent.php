<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Current URL utilities.
 *
 * @since 150424 Initial release.
 */
class UrlCurrent extends AbsBase
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
     * Current URL.
     *
     * @since 150424 Initial release.
     *
     * @return string Current URL.
     */
    public function __invoke(): string
    {
        if (!is_null($url = &$this->staticKey(__FUNCTION__, $scheme))) {
            return $url; // Cached this already.
        }
        $url = $this->scheme().'//'.$this->host().$this->uri();

        return $url;
    }

    /**
     * Current scheme; lowercase.
     *
     * @since 150424 Initial release.
     *
     * @return string Current scheme; lowercase.
     */
    public function scheme(): string
    {
        if (!is_null($scheme = &$this->staticKey(__FUNCTION__))) {
            return $scheme; // Cached this already.
        }
        $scheme = $this->isSsl() ? 'https' : 'http';

        return $scheme;
    }

    /**
     * Current host name; lowercase.
     *
     * @since 150424 Initial release.
     *
     * @param bool $no_port No port number? Defaults to `FALSE`.
     *
     * @note Some hosts include a port number in `$_SERVER['HTTP_HOST']`.
     *    That SHOULD be left intact for URL generation in almost every scenario.
     *    However, in a few other edge cases it may be desirable to exclude the port number.
     *    e.g., if the purpose of obtaining the host is to use it for email generation, or in a slug, etc.
     *
     * @return string Current host name; lowercase.
     */
    public function host(bool $no_port = false): string
    {
        if (!is_null($host = &$this->staticKey(__FUNCTION__, $no_port))) {
            return $host; // Cached this already.
        }
        $host = ''; // Initialize.
        if (!empty($_SERVER['HTTP_HOST'])) {
            $host = strtolower((string) $_SERVER['HTTP_HOST']);
        }
        if ($no_port) {
            $host = preg_replace('/\:[0-9]+$/', '', $host);
        }
        return $host;
    }

    /**
     * Current root host name; lowercase.
     *
     * @since 151002 Adding root host support.
     *
     * @param bool $no_port No port number? Defaults to `FALSE`.
     *
     * @note Some hosts include a port number in `$_SERVER['HTTP_HOST']`.
     *    That SHOULD be left intact for URL generation in almost every scenario.
     *    However, in a few other edge cases it may be desirable to exclude the port number.
     *    e.g., if the purpose of obtaining the host is to use it for email generation, or in a slug, etc.
     *
     * @return string Current root host name; lowercase.
     */
    public function rootHost(bool $no_port = false): string
    {
        if (!is_null($root_host = &$this->staticKey(__FUNCTION__, $no_port))) {
            return $root_host; // Cached this already.
        }
        $host      = $this->host($no_port);
        $parts     = explode('.', $host);
        $root_host = implode(array_slice($parts, -2));

        return $root_host;
    }

    /**
     * Current URI; with a leading `/`.
     *
     * @since 150424 Initial release.
     *
     * @return string Current URI; with a leading `/`.
     */
    public function uri(): string
    {
        if (!is_null($uri = &$this->staticKey(__FUNCTION__))) {
            return $uri; // Cached this already.
        }
        $uri = ''; // Initialize.
        if (!empty($_SERVER['REQUEST_URI'])) {
            $uri = (string) $_SERVER['REQUEST_URI'];
        }
        $uri = '/'.ltrim($uri, '/');

        return $uri;
    }

    /**
     * Current URI/path; with a leading `/`.
     *
     * @since 150424 Initial release.
     *
     * @return string Current URI/path; with a leading `/`.
     */
    public function path(): string
    {
        if (!is_null($path = &$this->staticKey(__FUNCTION__))) {
            return $path; // Cached this already.
        }
        $path = (string) parse_url($this->uri(), PHP_URL_PATH);
        $path = '/'.ltrim($path, '/');

        return $path;
    }

    /**
     * Current `index.php/path/info`.
     *
     * @since 150424 Initial release.
     *
     * @return string e.g., `index.php/path/info`.
     */
    public function pathInfo(): string
    {
        if (!is_null($path_info = &$this->staticKey(__FUNCTION__))) {
            return $path_info; // Cached this already.
        }
        $path_info = ''; // Initialize.
        if (isset($_SERVER['PATH_INFO'])) {
            $path_info = (string) $_SERVER['PATH_INFO'];
        }
        if (strpos($path_info, '?') !== false) {
            list($path_info) = explode('?', $path_info, 2);
        }
        $path_info = trim($path_info, '/');
        $path_info = str_replace('%', '%25', $path_info);

        return $path_info;
    }

    /**
     * Is an SSL scheme?
     *
     * @since 150424 Initial release.
     *
     * @return bool Is an SSL scheme?
     */
    public function isSsl(): bool
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (!empty($_SERVER['SERVER_PORT'])) {
            if ((integer) $_SERVER['SERVER_PORT'] === 443) {
                return ($is = true);
            }
        }
        if (!empty($_SERVER['HTTPS'])) {
            if (filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) {
                return ($is = true);
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if (strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0) {
                return ($is = true);
            }
        }
        return ($is = false);
    }

    /**
     * Current URL is on a localhost?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if current URL is on a localhost.
     */
    public function isLocalhost(): bool
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (defined('LOCALHOST') && LOCALHOST) {
            return ($is = true);
        }
        if (preg_match('/\b(?:localhost|127\.0\.0\.1)\b/i', $this->host())) {
            return ($is = true);
        }
        return ($is = false);
    }
}
