<?php
namespace WebSharks\Core\Classes;

/**
 * FS size utilities.
 *
 * @since 150424 Initial release.
 */
class FsSize extends AbsBase
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
     * Abbreviated byte notation for a particular file.
     *
     * @since 150424 Initial release.
     *
     * @param string $file Absolute path to an existing file.
     *
     * @return string If file exists, an abbreviated byte notation.
     */
    public function abbr($file)
    {
        if (!($file = (string) $file)) {
            return ''; // Empty.
        }
        if (!is_file($file) || !is_readable($file)) {
            return ''; // Not possible.
        }
        return $this->bytesAbbr((float) filesize($file));
    }

    /**
     * Abbreviated byte notation for file sizes.
     *
     * @since 150424 Initial release.
     *
     * @param float $bytes     File size in bytes. A (float) value.
     * @param int   $precision Number of decimals to use.
     *
     * @return string Byte notation.
     */
    public function bytesAbbr($bytes, $precision = 2)
    {
        $bytes      = max(0.0, (float) $bytes);
        $precision  = max(0, (integer) $precision);
        $units      = array('bytes', 'kbs', 'MB', 'GB', 'TB');
        $power      = floor(($bytes ? log($bytes) : 0) / log(1024));
        $abbr_bytes = round($bytes / pow(1024, $power), $precision);
        $abbr       = $units[min($power, count($units) - 1)];

        if ($abbr_bytes === (float) 1 && $abbr === 'bytes') {
            $abbr = 'byte'; // Quick fix.
        } elseif ($abbr_bytes === (float) 1 && $abbr === 'kbs') {
            $abbr = 'kb'; // Quick fix.
        }
        return $abbr_bytes.' '.$abbr;
    }

    /**
     * Converts an abbreviated byte notation into bytes.
     *
     * @since 150424 Initial release.
     *
     * @param string $string A string value in byte notation.
     *
     * @return float A float indicating the number of bytes.
     */
    public function abbrBytes($string)
    {
        $string = (string) $string;
        $regex  = '/^(?P<value>[0-9\.]+)\s*(?P<modifier>bytes|byte|kbs|kb|k|mb|m|gb|g|tb|t)$/i';

        if (!preg_match($regex, $string, $_m)) {
            return (float) 0;
        }
        $value    = (float) $_m['value'];
        $modifier = strtolower($_m['modifier']);
        unset($_m); // Housekeeping.

        switch ($modifier) {
            case 't':
            case 'tb':
                $value *= 1024;
                // Fall through.

            case 'g':
            case 'gb':
                $value *= 1024;
                // Fall through.

            case 'm':
            case 'mb':
                $value *= 1024;
                // Fall through.

            case 'k':
            case 'kb':
            case 'kbs':
                $value *= 1024;
        }
        return (float) $value;
    }
}
