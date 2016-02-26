<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * IP address utilities.
 *
 * @since 150424 Initial release.
 */
class Ip extends Classes\Core\Base\Core
{
    /**
     * Current visitor's IP address.
     *
     * @since 150424 Initial release.
     *
     * @return string Current visitor's IP address.
     *
     * @note Supports both IPv4 & IPv6 addresses.
     */
    public function current(): string
    {
        if (!is_null($ip = &$this->cacheKey(__FUNCTION__))) {
            return $ip; // Already cached this.
        }
        if ($this->c::isCli()) {
            throw new Exception('Not possible in CLI mode.');
        }
        $sources = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_VIA',
            'REMOTE_ADDR',
        ];
        foreach ($sources as $_source) {
            if (!empty($_SERVER[$_source]) && is_string($_SERVER[$_source])) {
                if (($_valid_public_ip = $this->getValidPublicFrom($_SERVER[$_source]))) {
                    return $ip = $_valid_public_ip; // IPv4 or IPv6 address.
                }
            } // unset($_valid_public_ip); // Housekeeping.
        } // unset($_source); // Housekeeping.

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return $ip = mb_strtolower((string) $_SERVER['REMOTE_ADDR']);
        }
        return $ip = 'unknown'; // Not possible.
    }

    /**
     * Region code for IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address.
     *
     * @return string Region code for IP address.
     */
    public function region(string $ip): string
    {
        if (($geo = $this->geoData($ip))) {
            return $geo->region;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Country code for IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address.
     *
     * @return string Country code for IP address.
     */
    public function country(string $ip): string
    {
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY']) // Save time.
            && $ip === $_SERVER['HTTP_CF_CONNECTING_IP'] ?? '' && $ip === $this->current()
            && mb_strlen((string) $_SERVER['HTTP_CF_IPCOUNTRY']) === 2) {
            return (string) $_SERVER['HTTP_CF_IPCOUNTRY'];
        }
        if (($geo = $this->geoData($ip))) {
            return $geo->country;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Geo data for IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address.
     *
     * @return \stdClass|bool Geo data for IP address.
     */
    protected function geoData(string $ip)
    {
        # Valid  IP. Do we have one?

        if (!($ip = $this->c::mbTrim(mb_strtolower($ip)))) {
            return false; // Not possible.
        }
        # Check object cache. Did we already do this?

        if (!is_null($geo = &$this->cacheKey(__FUNCTION__, $ip))) {
            return $geo; // Already cached this.
        }
        # Check filesystem cache. Did we already do this?

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw new Exception('Missing cache directory.');
        }
        $ip_sha1               = sha1($ip); // Needed below.
        $cache_dir             = $this->App->Config->©fs_paths['©cache_dir'].'/ip-geo-data/'.$this->c::sha1ModShardId($ip_sha1, true);
        $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
        $cache_file            = $cache_dir.'/'.$ip_sha1.'.json';

        if (is_file($cache_file) && filemtime($cache_file) >= strtotime('-30 days')) {
            $cache      = json_decode(file_get_contents($cache_file));
            return $geo = is_object($cache) ? $cache : false;
        }
        # Initialize request-related variables.

        $region = $country = ''; // Initialize.

        # Query geoPlugin service.

        $response = $this->c::remoteRequest(
            'GET::http://www.geoplugin.net/json.gp?ip='.urlencode($ip),
            ['max_con_secs' => 5, 'max_stream_secs' => 5]
        );
        if (!$response || !is_object($json = json_decode($response))) {
            // Fill object cache, but do not create a cache file.
            return $geo = false; // Connection failure.
        }
        # Parse response; i.e., try to fill geo data.

        if (!empty($json->geoplugin_regionCode)) {
            $region = (string) $json->geoplugin_regionCode;
            $region = $this->c::mbStrPad($region, 2, '0', STR_PAD_LEFT);
            $region = mb_strtoupper($region);
        }
        if (!empty($json->geoplugin_countryCode)) {
            $country = (string) $json->geoplugin_countryCode;
            $country = mb_strtoupper($country);
        }
        # Fill object cache; i.e., `\stdClass` or `false`.

        $geo = (object) compact('region', 'country');
        if (mb_strlen($geo->region) !== 2 || mb_strlen($geo->country) !== 2) {
            $geo = false; // Invalid (or insufficient) data.
        }
        # Cache geo data; i.e., `\stdClass` or `false`.

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        file_put_contents($cache_file, json_encode($geo));

        # Return object; or `false` on failure.

        return $geo; // `\stdClass` or `false`.
    }

    /**
     * Looks for a valid/public IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $list_of_possible_ips A single IP, or a comma-delimited list of IPs.
     *
     * @return string A valid/public IP address (if one is found); else an empty string.
     */
    protected function getValidPublicFrom(string $list_of_possible_ips): string
    {
        if (!($list_of_possible_ips = $this->c::mbTrim($list_of_possible_ips))) {
            return ''; // Not possible; i.e., empty string.
        }
        foreach (preg_split('/[\s;,]+/u', $list_of_possible_ips, -1, PREG_SPLIT_NO_EMPTY) as $_possible_ip) {
            if (($_valid_public_ip = filter_var(mb_strtolower($_possible_ip), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))) {
                return $_valid_public_ip; // A valid public IPv4 or IPv6 address.
            }
        } // unset($_possible_ip, $_valid_public_ip); // Housekeeping.

        return ''; // Default return value.
    }
}
