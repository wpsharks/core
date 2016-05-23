<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * URL scheme utilities.
 *
 * @since 150424 Initial release.
 */
class UrlScheme extends Classes\Core\Base\Core implements Interfaces\UrlConstants
{
    /**
     * Set the scheme for a URL.
     *
     * @since 150424 Initial release.
     *
     * @param string $url    Absolute URL that includes a scheme (or a `//` scheme).
     * @param string $scheme ``|`//`, `current`, `default`, `relative`, `https`, `http`, or another.
     *
     * @return string $url URL w/ a specific scheme.
     */
    public function set(string $url, string $scheme = ''): string
    {
        if (!$scheme || $scheme === '//') {
            $scheme = '//'; # Inherits.
        } elseif ($scheme === 'current') {
            $scheme = $this->c::currentScheme();
        } elseif ($scheme === 'default') {
            $scheme = $this->App->Config->©urls['©default_scheme'];
        }
        if (!$scheme) {
            throw new Exception('Empty scheme.');
        }
        if ($scheme === '//') {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', '//', $url);
        } elseif ($scheme === 'relative') {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'[^\/]*/u', '', $url);
        } else {
            $url = preg_replace('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', $scheme.'://', $url);
        }
        return $url;
    }
}
