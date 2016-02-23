<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Slug utilities.
 *
 * @since 150424 Initial release.
 */
class Slug extends Classes\Core implements Interfaces\SlugConstants
{
    /**
     * Validate slug.
     *
     * @since 150424 Initial release.
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
     * @since 150424 Reserved slugs.
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
            return $is = true;
        }
        if (in_array($slug, $this::SLUG_RESERVED_STRINGS, true)) {
            return $is = true;
        }
        foreach ($this::SLUG_RESERVED_REGEX_FRAGS as $_regex_frag) {
            if (preg_match('/^'.$_regex_frag.'$/ui', $slug)) {
                return $is = true;
            }
        } // unset($_regex_frag); // Housekeeping.

        return $is = false;
    }

    /**
     * Convert slug to name.
     *
     * @since 150424 Initial release.
     *
     * @param string $slug Slug to convert to name.
     *
     * @return string Name; based on slug.
     */
    public function toName(string $slug): string
    {
        $name = $slug; // Working copy.
        $name = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $name);
        $name = c\mb_ucwords($name);
        $name = c\mb_trim($name);

        return $name;
    }

    /**
     * Convert slug to acronym.
     *
     * @since 160220 Initial release.
     *
     * @param string $slug Slug to convert to acronym.
     *
     * @return string Acronym; based on slug.
     */
    public function toAcronym(string $slug): string
    {
        return c\name_to_acronym($this->toName($slug));
    }

    /**
     * Convert slug to var.
     *
     * @since 160220 Initial release.
     *
     * @param string $slug Slug to convert to var.
     *
     * @return string Var; based on slug.
     */
    public function toVar(string $slug): string
    {
        $var = $slug; // Working copy.
        $var = mb_strtolower(c\force_ascii($var));
        $var = preg_replace('/[^a-z0-9]+/u', '_', $var);
        $var = c\mb_trim($var, '', '_');

        if ($var && !preg_match('/^[a-z]/u', $var)) {
            $var = 'x'.$var; // Force `^[a-z]`.
        }
        return $var;
    }
}
