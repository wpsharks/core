<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CDN utilities.
 *
 * @since 150424 Initial release.
 */
class Cdn extends Classes\AbsBase
{
    /**
     * CDN URL generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $uri URI to serve via CDN.
     *
     * @return string Full URL to file.
     */
    public function url(string $uri): string
    {
        if (!$this->App->Config->cdn['base_url']) {
            throw new Exception('Missing CDN base URL.');
        }
        $url = $this->App->Config->cdn['base_url'];
        $url .= '/'.$this->Utils->Trim->l($uri, '/');

        return $url;
    }
}
