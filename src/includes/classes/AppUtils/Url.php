<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL utilities.
 *
 * @since 151121 URL utilities.
 */
class Url extends Classes\AbsBase implements Interfaces\UrlConstants
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
        $uri = $uri ? $this->Utils->Trim->l($uri, '/') : '';
        $url = $this->Utils->UrlScheme->set('//'.$host.'/'.$uri, $scheme);

        if ($uri && $cdn_filter) {
            $url = $this->Utils->Cdn->filter($url);
        }
        return $url;
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
    public function toCur(string $uri = '', string $scheme = '', bool $cdn_filter = true): string
    {
        $host = $this->Utils->UrlCurrent->host();
        $uri  = $uri ? $this->Utils->Trim->l($uri, '/') : '';
        $url  = $this->Utils->UrlScheme->set('//'.$host.'/'.$uri, $scheme);

        if ($uri && $cdn_filter) {
            $url = $this->Utils->Cdn->filter($url);
        }
        return $url;
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
}
