<?php
namespace WebSharks\Core\Traits;

/**
 * Current URL Utilities.
 *
 * @since 150424 Initial release.
 */
trait UrlCurrentUtils
{
    abstract protected function envIsSsl();
    abstract protected function setUrlScheme($url, $scheme = null);
    abstract protected function &staticKey($function, $args = array());

    /**
     * Current scheme; lowercase.
     *
     * @since 150424 Initial release.
     *
     * @return string Current scheme; lowercase.
     */
    protected function currentUrlScheme()
    {
        if (!is_null($scheme = &$this->staticKey(__FUNCTION__))) {
            return $scheme; // Cached this already.
        }
        $scheme = $this->envIsSsl() ? 'https' : 'http';

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
     *    e.g. if the purpose of obtaining the host is to use it for email generation, or in a slug, etc.
     *
     * @return string Current host name; lowercase.
     */
    protected function currentUrlHost($no_port = false)
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
     * Current URI; with a leading `/`.
     *
     * @since 150424 Initial release.
     *
     * @return string Current URI; with a leading `/`.
     */
    protected function currentUrlUri()
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
    protected function currentUrlPath()
    {
        if (!is_null($path = &$this->staticKey(__FUNCTION__))) {
            return $path; // Cached this already.
        }
        $path = (string) parse_url($this->currentUrlUri(), PHP_URL_PATH);
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
    protected function currentUrlPathInfo()
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
     * Current URL w/ specific scheme.
     *
     * @since 150424 Initial release.
     *
     * @param string|null $scheme Defaults to a `NULL` value.
     *
     * @return string Current URL w/ specific scheme.
     */
    protected function currentUrl($scheme = null)
    {
        if (!is_null($url = &$this->staticKey(__FUNCTION__, $scheme))) {
            return $url; // Cached this already.
        }
        $url = '//'.$this->currentUrlHost().$this->currentUrlUri();
        $url = $this->setUrlScheme($url, $scheme);

        return $url;
    }
}
