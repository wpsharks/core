<?php
/**
 * File size utilities.
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
 * File size utilities.
 *
 * @since 150424 Initial release.
 */
class FileSize extends Classes\Core\Base\Core
{
    /**
     * Abbreviated byte notation.
     *
     * @since 150424 Initial release.
     *
     * @param string $file Absolute file path.
     *
     * @return string Abbreviated byte notation.
     */
    public function abbr(string $file): string
    {
        if (!$file) {
            return ''; // Empty.
        } elseif (!is_file($file)) {
            return ''; // Not possible.
        } elseif (!is_readable($file)) {
            return ''; // Not possible.
        }
        return $this->bytesAbbr(filesize($file));
    }

    /**
     * Abbreviated byte notation.
     *
     * @since 150424 Initial release.
     *
     * @param int|float $bytes File size in bytes.
     * @note This accepts `float` for back compat. only.
     *
     * @param int $precision Number of decimals to use.
     *
     * @return string Abbreviated byte notation.
     */
    public function bytesAbbr(float $bytes, int $precision = 2): string
    {
        $bytes      = max(0, (int) $bytes);
        $precision  = max(0, $precision);

        $units      = ['bytes', 'kbs', 'MB', 'GB', 'TB'];
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
     * Bytes represented by notation.
     *
     * @since 150424 Initial release.
     * @since 170824.30708 Returns `int` instead of `float`.
     *
     * @param string $string A string byte notation.
     *
     * @return int Bytes represented by notation.
     */
    public function abbrBytes(string $string): int
    {
        if (!preg_match('/^(?<value>[0-9\.]+)\s*(?<modifier>bytes|byte|kbs|kb|k|mb|m|gb|g|tb|t)$/ui', $string, $_m)) {
            return 0; // Default value of `0`, failure.
        }
        $value    = (float) $_m['value'];
        $modifier = mb_strtolower($_m['modifier']);
        // unset($_m); // Housekeeping.

        switch ($modifier) {
            case 't':
            case 'tb':
                $value *= 1024;
                // Fall through.
                // no break
            case 'g':
            case 'gb':
                $value *= 1024;
                // Fall through.
                // no break
            case 'm':
            case 'mb':
                $value *= 1024;
                // Fall through.
                // no break
            case 'k':
            case 'kb':
            case 'kbs':
                $value *= 1024;
        }
        return (int) $value;
    }

    /**
     * Determines remote file size.
     *
     * @since 170824.30708 Remote file size.
     *
     * @param string $url            URL to check size of.
     * @param int    $expires_after  Cache expires after (in seconds).
     * @param bool   $report_failure If true, return `-1` on failure.
     *
     * @return int Remote file size, in bytes.
     * @note If `$report_failure` is true, `-1` indicates failure.
     * For example, if file does not exist, or it has no `content-length` header.
     */
    public function remoteBytes(string $url, bool $report_failure = false, int $expires_after = 86400): int
    {
        if (!$url) { // If no URL, no file size.
            return $report_failure ? -1 : 0;
        }
        if (($bytes = $this->c::dirCacheGet(__METHOD__, $url)) !== null) {
            return $bytes; // Cached this already.
        } // Get from an existing cache if at all possible.

        $r = $this->c::remoteResponse('HEAD::'.$url, [
            'max_con_secs' => 5, 'max_stream_secs' => 5,
        ]);
        if ($r->code === 200 && isset($r->headers['content-length'])) {
            $bytes = (int) $r->headers['content-length'];
        } else {
            $bytes = $report_failure ? -1 : 0; // Not possible.
        }
        $this->c::dirCacheSet(__METHOD__, $url, $bytes, $expires_after);
        return $bytes; // Cached for future reference.
    }
}
