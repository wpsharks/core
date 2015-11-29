<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Slug utilities.
 *
 * @since 150424 Initial release.
 */
class Slug extends Classes\AbsBase implements Interfaces\SlugConstants
{
    /**
     * Validate slug.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $slug Slug to validate.
     *
     * @return bool True if slug is valid.
     */
    public function isValid(string $slug): bool
    {
        return (bool) preg_match($this::SLUG_REGEX_VALID, $slug);
    }

    /**
     * Is slug reserved?
     *
     * @since 15xxxx Reserved slugs.
     *
     * @param string $slug Slug to check.
     *
     * @return bool True if slug is reserved.
     */
    public function isReserved(string $slug): bool
    {
        $slug = mb_strtolower($slug);
        $slug = str_replace(['-', '_', '.'], '', $slug);

        if (!is_null($is = &$this->cacheKey(__FUNCTION__, $slug))) {
            return $is; // Cached this already.
        }
        if (mb_strlen($slug) < 3) {
            return ($is = true);
        }
        if (in_array($slug, $this::SLUG_RESERVED_STRINGS, true)) {
            return ($is = true);
        }
        foreach ($this::SLUG_RESERVED_REGEX_FRAGS as $_regex_frag) {
            if (preg_match('/^'.$_regex_frag.'$/ui', $slug)) {
                return ($is = true);
            }
        } // unset($_regex_frag); // Housekeeping.

        return ($is = false);
    }

    /**
     * Convert slug to name.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $slug Slug to convert to name.
     *
     * @return string Name; based on slug.
     */
    public function toName(string $slug): string
    {
        $name = $slug; // Working copy.
        $name = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $name);
        $name = $this->Utils->UcWords($name);
        $name = $this->Utils->Trim($name);

        return $name;
    }
}
