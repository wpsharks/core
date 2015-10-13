<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * IP address utilities.
 *
 * @since 150424 Initial release.
 */
class Ip extends AbsBase
{
    protected $FsDir;
    protected $UrlRemote;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        FsDir $FsDir,
        UrlRemote $UrlRemote
    ) {
        parent::__construct();

        $this->FsDir     = $FsDir;
        $this->UrlRemote = $UrlRemote;
    }

    /**
     * Get the current visitor's real IP address.
     *
     * @since 150424 Initial release.
     *
     * @return string Real IP address; else `unknown` on failure.
     *
     * @note This supports both IPv4 and IPv6 addresses.
     */
    public function current(): string
    {
        if (!is_null($ip = &$this->staticKey(__FUNCTION__))) {
            return $ip; // Already cached this.
        }
        $sources = array(
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_VIA',
            'REMOTE_ADDR',
        );
        foreach ($sources as $_source) {
            if (!empty($_SERVER[$_source]) && is_string($_SERVER[$_source])) {
                if (($_valid_public_ip = $this->getValidPublicFrom($_SERVER[$_source]))) {
                    return ($ip = $_valid_public_ip); // IPv4 or IPv6 address.
                }
            } // unset($_valid_public_ip); // Housekeeping.
        } // unset($_source); // Housekeeping.

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return ($ip = strtolower((string) $_SERVER['REMOTE_ADDR']));
        }
        return ($ip = 'unknown'); // Not possible.
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
    public function getValidPublicFrom(string $list_of_possible_ips): string
    {
        if (!($list_of_possible_ips = trim($list_of_possible_ips))) {
            return ''; // Not possible; i.e., empty string.
        }
        foreach (preg_split('/[\s;,]+/', $list_of_possible_ips, null, PREG_SPLIT_NO_EMPTY) as $_possible_ip) {
            if (($_valid_public_ip = filter_var(strtolower($_possible_ip), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))) {
                return $_valid_public_ip; // A valid public IPv4 or IPv6 address.
            }
        }
        unset($_possible_ip, $_valid_public_ip); // Housekeeping.

        return ''; // Default return value.
    }

    /**
     * Geographic region code for given IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address to pull geographic data for.
     *
     * @return string Geographic region code for given IP address; if possible.
     */
    public function region(string $ip): string
    {
        if (($geo = $this->geoData($ip))) {
            return $geo->region;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Current user's geographic region code.
     *
     * @since 150424 Initial release.
     *
     * @return string Current user's geographic region code; if possible.
     */
    public function currentRegion(): string
    {
        if (($geo = $this->geoData($this->current()))) {
            return $geo->region;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Geographic country code for given IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address to pull geographic data for.
     *
     * @return string Geographic country code for given IP address; if possible.
     */
    public function country(string $ip): string
    {
        if (($geo = $this->geoData($ip))) {
            return $geo->country;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Current user's geographic country code.
     *
     * @since 150424 Initial release.
     *
     * @return string Current user's geographic country code; if possible.
     */
    public function currentCountry(): string
    {
        if (($geo = $this->geoData($this->current()))) {
            return $geo->country;
        }
        return ''; // Empty string on failure.
    }

    /**
     * Geographic location data from IP address.
     *
     * @since 150424 Initial release.
     *
     * @param string $ip An IP address to query.
     *
     * @throws \exception If unable to create cache directory.
     *
     * @return \stdClass|bool Geo location data from IP address.
     */
    public function geoData(string $ip)
    {
        # Valid the input IP address; do we have one?

        if (!($ip = trim(strtolower($ip)))) {
            return false; // Not possible.
        }
        # Check the static object cache.

        if (!is_null($geo = &$this->staticKey(__FUNCTION__, $ip))) {
            return $geo; // Already cached this.
        }
        # Check the filesystem cache; i.e., tmp directory.

        $cache_dir  = $this->FsDir->tmp().'/ip-geo-data';
        $cache_file = $cache_dir.'/'.sha1($ip).'.json';

        if (is_file($cache_file) && filemtime($cache_file) >= strtotime('-30 days')) {
            return ($geo = json_decode(file_get_contents($cache_file)));
        }
        # Initialize request-related variables.

        $region = $country = ''; // Initialize.

        # Perform remote request to the geoPlugin service.

        $response = $this->UrlRemote->request(
            'GET::http://www.geoplugin.net/json.gp?ip='.urlencode($ip),
            ['max_con_secs' => 5, 'max_stream_secs' => 5]
        );
        if (!$response || !is_object($json = json_decode($response))) {
            return ($geo = false); // Connection failure.
        }
        # Parse response from geoPlugin service.

        if (!empty($json->geoplugin_regionCode)) {
            $region = strtoupper(str_pad((string) $json->geoplugin_regionCode, 2, '0', STR_PAD_LEFT));
        }
        if (!empty($json->geoplugin_countryCode)) {
            $country = strtoupper((string) $json->geoplugin_countryCode);
        }
        # Fill the object cache; based on data validation here.

        $geo = (object) compact('region', 'country'); // Initialize.
        if (strlen($geo->region) !== 2 || strlen($geo->country) !== 2) {
            $geo = false; // Invalid (or insufficient) data.
        }
        # Cache validated response from geoPlugin service.

        if (!is_dir($cache_dir) && !mkdir($cache_dir, 0777, true)) {
            throw new \exception('Unable to create `ip-geo-data` cache directory.');
        }
        file_put_contents($cache_file, json_encode($geo));

        # Return either an object or `FALSE` on failure.

        return $geo; // An object (hopefully).
    }
}
