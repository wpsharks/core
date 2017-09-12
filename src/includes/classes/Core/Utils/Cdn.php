<?php
/**
 * CDN utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * CDN utilities.
 *
 * @since 150424 Initial release.
 */
class Cdn extends Classes\Core\Base\Core implements Interfaces\MimeConstants
{
    /**
     * Extensions.
     *
     * @since 150424
     *
     * @var string[]
     */
    protected $static_exts;

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

        $this->static_exts = array_keys($this::MIME_TYPES);
        $this->static_exts = array_diff($this->static_exts, ['php']);
    }

    /**
     * CDN URL generator.
     *
     * @since 150424 Initial release.
     *
     * @param string $uri    URI to serve via CDN.
     * @param string $scheme Specific scheme?
     *
     * @return string Output URL.
     */
    public function url(string $uri, string $scheme = ''): string
    {
        if (!($host = $this->App->Config->©urls['©hosts']['©cdn'])) {
            throw $this->c::issue('Missing CDN host name.');
        }
        $uri = $uri ? $this->c::mbLTrim($uri, '/') : '';
        $uri = $uri ? '/'.$uri : ''; // Force leading slash.

        if ($uri && defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            $uri = preg_replace('/\.min\.js($|[?])/u', '.js${1}', $uri);
        } // This allows developers to debug uncompressed JS files.

        $base_path = $this->App->Config->©urls['©base_paths']['©cdn'];
        $base_path = $base_path && $uri ? $this->c::mbRTrim($base_path, '/') : $base_path;

        return $url = $this->c::setScheme('//'.$host.$base_path.$uri, $scheme);
    }

    /**
     * CDN URL filter.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string Output URL, URI, or query string w/ a leading `?`.
     */
    public function filter(string $url_uri_qsl): string
    {
        if (!$this->App->Config->©urls['©cdn_filter_enable']) {
            return $url_uri_qsl; // Nothing to do.
        }
        if (!$this->App->Config->©urls['©hosts']['©cdn']) {
            return $url_uri_qsl; // Nothing to do.
        }
        if (!($parts = $this->c::parseUrl($url_uri_qsl))) {
            return $url_uri_qsl; // Not possible.
        }
        if (empty($parts['path'])) {
            return $url_uri_qsl; // Not applicable.
        }
        if (!empty($parts['user']) || !empty($parts['pass'])) {
            return $url_uri_qsl; // Not applicable.
        }
        if (!($ext = $this->c::fileExt($parts['path']))) {
            return $url_uri_qsl; // No extension.
        }
        if (!in_array($ext, $this->static_exts, true)) {
            return $url_uri_qsl; // Not applicable.
        }
        if (!empty($parts['host'])) { // Has a host name?
            $host_root = $this->c::parseUrlHost($parts['host'])['root'];
            $app_root  = $this->App->Config->©urls['©hosts']['©roots']['©app'];
            if (!$host_root || $this->c::mbStrCaseCmp($host_root, $app_root) !== 0) {
                return $url_uri_qsl; // Not applicable.
            } // Also check for an empty/invalid root here.
        } // If there is a host part, it must have an app root!

        $uri = $parts; // w/ URI parts only.
        unset($uri['scheme'], $uri['host'], $uri['port']);
        $uri = $this->c::unparseUrl($uri); // The URI only.

        return $this->url($uri, $parts['scheme'] ?? '');
    }
}
