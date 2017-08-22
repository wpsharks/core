<?php
/**
 * Current URL utilities.
 *
 * @author @jaswrks
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
 * Current URL utilities.
 *
 * @since 150424 Initial release.
 */
class UrlCurrent extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if ($this->c::isCli()) {
            throw $this->c::issue('Not possible in CLI mode.');
        }
    }

    /**
     * Current URL.
     *
     * @since 150424 Initial release.
     *
     * @param bool $canonical Canonical?
     *
     * @return string Current URL.
     */
    public function __invoke(bool $canonical = false): string
    {
        if (($url = &$this->cacheKey(__FUNCTION__, $canonical)) !== null) {
            return $url; // Cached this already.
        }
        return $url = $this->scheme().'://'.$this->host().$this->uri($canonical);
    }

    /**
     * Current request method.
     *
     * @since 17xxxx Current method.
     *
     * @return string Current method.
     */
    public function method(): string
    {
        if (($method = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $method; // Cached this already.
        }
        return $method = mb_strtoupper($_SERVER['REQUEST_METHOD']);
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
        if (($scheme = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $scheme; // Cached this already.
        }
        return $scheme = $this->isSsl() ? 'https' : 'http';
    }

    /**
     * Current host name; lowercase.
     *
     * @since 150424 Initial release.
     *
     * @param bool $with_port With port number?
     *
     * @return string Current host name; lowercase.
     */
    public function host(bool $with_port = true): string
    {
        if (($host = &$this->cacheKey(__FUNCTION__, $with_port)) !== null) {
            return $host; // Cached this already.
        }
        $host = mb_strtolower($_SERVER['HTTP_HOST']);

        if (!$with_port) { // Strip port number?
            $host = preg_replace('/\:[0-9]+$/u', '', $host);
        }
        return $host; // With or without port number.
    }

    /**
     * Current port number (as string).
     *
     * @since 170124.74961 Enhancing support for ports.
     *
     * @return string Current port (as string).
     */
    public function port(): string
    {
        if (($port = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $port; // Cached this already.
        }
        return $port = (explode(':', $this->host(), 2) + ['', ''])[1];
    }

    /**
     * Current root host name; lowercase.
     *
     * @since 151002 Adding root host support.
     *
     * @param bool $with_port With port number?
     *
     * @return string Current root host name; lowercase.
     */
    public function rootHost(bool $with_port = true): string
    {
        if (($root_host = &$this->cacheKey(__FUNCTION__, $with_port)) !== null) {
            return $root_host; // Cached this already.
        }
        $name_parts       = explode('.', $this->host(false));
        $root_name        = implode('.', array_slice($name_parts, -2));
        $port             = $this->port(); // Might be needed in next line.
        return $root_host = $root_name.(!$with_port ? '' : (isset($port[0]) ? ':'.$port : ''));
    }

    /**
     * Current root port number (as string).
     *
     * @since 170124.74961 Enhancing support for ports.
     *
     * @return string Current root port (as string).
     */
    public function rootPort(): string
    {
        if (($port = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $port; // Cached this already.
        }
        return $port = (explode(':', $this->rootHost(), 2) + ['', ''])[1];
    }

    /**
     * Current URI; with a leading `/`.
     *
     * @since 150424 Initial release.
     *
     * @param bool $canonical Canonical?
     *
     * @return string Current URI; with a leading `/`.
     */
    public function uri(bool $canonical = false): string
    {
        if (($uri = &$this->cacheKey(__FUNCTION__, $canonical)) !== null) {
            return $uri; // Cached this already.
        }
        $uri = $_SERVER['REQUEST_URI'];
        $uri = '/'.$this->c::mbLTrim($uri, '/');

        if ($canonical) { // Strip query/fragment.
            $uri = preg_split('/[?#]/u', $uri, 2)[0];
        }
        return $uri; // With or without query/fragment.
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
        if (($path = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $path; // Cached this already.
        }
        $path        = (string) parse_url($this->uri(), PHP_URL_PATH);
        return $path = '/'.$this->c::mbLTrim($path, '/');
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
        if (($path_info = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $path_info; // Cached this already.
        }
        $path_info = ''; // Initialize.

        if (isset($_SERVER['PATH_INFO'])) {
            $path_info = $_SERVER['PATH_INFO'];
        }
        if (mb_strpos($path_info, '?') !== false) {
            list($path_info) = explode('?', $path_info, 2);
        }
        $path_info        = $this->c::mbTrim($path_info, '/');
        return $path_info = str_replace('%', '%25', $path_info);
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
        if (($is = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $is; // Cached this already.
        }
        if (!empty($_SERVER['SERVER_PORT'])) {
            if ((int) $_SERVER['SERVER_PORT'] === 443) {
                return $is = true;
            }
        }
        if (!empty($_SERVER['HTTPS'])) {
            if (filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) {
                return $is = true;
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if ($this->c::mbStrCaseCmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0) {
                return $is = true;
            }
        }
        return $is = false;
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
        if (($is = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $is; // Cached this already.
        }
        if (defined('LOCALHOST') && LOCALHOST) {
            return $is = true;
        } elseif (preg_match('/(?:\b(?:localhost|127\.0\.0\.1)\b|\.vm)$/ui', $this->host(false))) {
            return $is = true;
        }
        return $is = false;
    }
}
