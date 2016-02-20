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
 * Var utilities.
 *
 * @since 160220 Initial release.
 */
class Vars extends Classes\AppBase implements Interfaces\VarConstants
{
    /**
     * Validate var.
     *
     * @since 160220 Initial release.
     *
     * @param string $var Var to validate.
     *
     * @return bool True if var is valid.
     */
    public function isValid(string $var): bool
    {
        return (bool) preg_match($this::VAR_REGEX_VALID, $var);
    }

    /**
     * Convert var to name.
     *
     * @since 160220 Initial release.
     *
     * @param string $var Var to convert to name.
     *
     * @return string Name; based on var.
     */
    public function toName(string $var): string
    {
        $name = $var; // Working copy.
        $name = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $name);
        $name = c\mb_ucwords($name);
        $name = c\mb_trim($name);

        return $name;
    }

    /**
     * Convert var to acronym.
     *
     * @since 160220 Initial release.
     *
     * @param string $var Var to convert to acronym.
     *
     * @return string Acronym; based on var.
     */
    public function toAcronym(string $var): string
    {
        return c\name_to_acronym($this->toName($var));
    }

    /**
     * Convert var to slug.
     *
     * @since 160220 Initial release.
     *
     * @param string $var Var to convert to slug.
     *
     * @return string Slug; based on var.
     */
    public function toSlug(string $var): string
    {
        $slug = $var; // Working copy.
        $slug = mb_strtolower(c\force_ascii($slug));
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);
        $slug = c\mb_trim($slug, '', '-');

        if ($slug && !preg_match('/^[a-z]/u', $slug)) {
            $slug = 'x'.$slug; // Force `^[a-z]`.
        }
        return $slug;
    }
}
