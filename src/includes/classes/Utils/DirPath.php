<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * DirPath utilities.
 *
 * @since 150424 Initial release.
 */
class DirPath extends Classes\AppBase implements Interfaces\FsConstants
{
    /**
     * Normalizes directory separators deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value                Any input value.
     * @param bool  $allow_trailing_slash Optional; defaults to a FALSE value.
     *
     * @return string|array|object Normalized directory/file path.
     */
    public function normalize($value, bool $allow_trailing_slash = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $stream_wrapper_regex = '/^(?<stream_wrapper>'.$this::FS_REGEX_FRAG_STREAM_WRAPPER.')/uS';
        $drive_prefix_regex   = '/^(?<drive_letter>'.$this::FS_REGEX_FRAG_DRIVE_PREFIX.')/uS';

        if (mb_strpos($string, '://') !== false && preg_match($stream_wrapper_regex, $string, $stream_wrapper)) {
            $string = preg_replace($stream_wrapper_regex, '', $string);
        }
        if (mb_strpos($string, ':') !== false && preg_match($drive_prefix_regex, $stringm, $drive_prefix)) {
            $string = preg_replace($stream_wrapper_regex, '', $string);
        }
        $string = str_replace(array(DIRECTORY_SEPARATOR, '\\', '/'), '/', $string);
        $string = preg_replace('/\/+/u', '/', $string); // Remove extra slashes.
        $string = $allow_trailing_slash ? $string : c\mb_rtrim($string, '/');

        if (!empty($drive_prefix[0])) {
            $string = mb_strtoupper($drive_prefix[0]).$string;
        }
        if (!empty($stream_wrapper[0])) {
            $string = mb_strtolower($stream_wrapper[0]).$string;
        }
        return $string;
    }
}
