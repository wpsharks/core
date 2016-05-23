<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

// `Vars` and function `vars` CAN coexist here.

/**
 * Var utilities.
 *
 * @since 160220 Initial release.
 */
class Vars extends Classes\Core\Base\Core implements Interfaces\VarConstants
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
        $name = $this->c::mbUcWords($name);
        $name = $this->c::mbTrim($name);

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
        return $this->c::nameToAcronym($this->toName($var));
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
        $slug = mb_strtolower($this->c::forceAscii($slug));
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);
        $slug = $this->c::mbTrim($slug, '', '-');

        if ($slug && !preg_match('/^[a-z]/u', $slug)) {
            $slug = 'x'.$slug; // Force `^[a-z]`.
        }
        return $slug;
    }
}
