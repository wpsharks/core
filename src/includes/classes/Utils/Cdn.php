<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CDN utilities.
 *
 * @since 150424 Initial release.
 */
class Cdn extends Classes\AppBase implements Interfaces\MimeConstants
{
    /**
     * Extensions.
     *
     * @since 15xxxx
     *
     * @type string[]
     */
    protected $static_exts;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->static_exts = array_keys($this::MIME_TYPES);
        $this->static_exts = array_diff($this->static_exts, ['php']);
    }

    /**
     * CDN URL generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $uri    URI to serve via CDN.
     * @param string $scheme Specific scheme?
     *
     * @return string Output URL.
     */
    public function url(string $uri, string $scheme = ''): string
    {
        if (!$this->App->Config->urls['hosts']['cdn']) {
            throw new Exception('Missing CDN host name.');
        }
        $uri = $uri ? c\mb_ltrim($uri, '/') : '';
        $url = '//'.$this->App->Config->urls['hosts']['cdn'].'/'.$uri;
        $url = c\set_scheme($url, $scheme);

        return $url;
    }

    /**
     * CDN URL generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $uri    URI to serve via CDN.
     * @param string $scheme Specific scheme?
     *
     * @return string Output URL.
     */
    public function s3Url(string $uri, string $scheme = ''): string
    {
        if (!$this->App->Config->urls['hosts']['cdn_s3']) {
            throw new Exception('Missing CDN S3 host name.');
        }
        $uri = $uri ? c\mb_ltrim($uri, '/') : '';
        $url = '//'.$this->App->Config->urls['hosts']['cdn_s3'].'/'.$uri;
        $url = c\set_scheme($url, $scheme);

        return $url;
    }

    /**
     * CDN URL filter.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string Output URL, URI, or query string w/ a leading `?`.
     */
    public function filter(string $url_uri_qsl): string
    {
        if (!$this->App->Config->urls['cdn_filter_enable']) {
            return $url_uri_qsl; // Nothing to do.
        }
        if (!$this->App->Config->urls['hosts']['cdn']) {
            return $url_uri_qsl; // Nothing to do.
        }
        if (!($parts = c\parse_url($url_uri_qsl))) {
            return $url_uri_qsl; // Not possible.
        }
        if (empty($parts['path'])) {
            return $url_uri_qsl; // Not applicable.
        }
        if (!empty($parts['user']) || !empty($parts['pass'])) {
            return $url_uri_qsl; // Not applicable.
        }
        if (!($ext = c\file_ext($parts['path']))) {
            return $url_uri_qsl; // No extension.
        }
        if (!in_array($ext, $this->static_exts, true)) {
            return $url_uri_qsl; // Not applicable.
        }
        if (!empty($parts['host'])) { // Has a host name?
            $host_root = c\parse_url_host($parts['host'])['root'];
            $app_root  = $this->App->Config->urls['hosts']['roots']['app'];
            if (!$host_root || c\mb_strcasecmp($host_root, $app_root) !== 0) {
                return $url_uri_qsl; // Not applicable.
            } // Also check for an empty/invalid root here.
        } // If there is a host part, it must have an app root!

        $uri = $parts; // w/ URI parts only.
        unset($uri['scheme'], $uri['host'], $uri['port']);
        $uri = c\unparse_url($uri);

        if (mb_stripos($uri, '/s3/') === 0) {
            $uri = mb_substr($uri, 3); // Remove `/s3`.
            return $this->s3Url($uri, $parts['scheme'] ?? '');
        }
        return $this->url($uri, $parts['scheme'] ?? '');
    }
}
