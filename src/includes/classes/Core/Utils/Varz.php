<?php
/**
 * Var utilities.
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
 * Var utilities.
 *
 * @since 160220 Initial release.
 */
class Varz extends Classes\Core\Base\Core implements Interfaces\VarConstants
{
    /**
     * Validate var.
     *
     * @since 160220 Initial release.
     *
     * @param string $var    Var.
     * @param bool   $strict Strict?
     *
     * @return bool True if var is valid.
     */
    public function isValid(string $var, bool $strict = true): bool
    {
        return (bool) preg_match($strict ? $this::VAR_STRICT_REGEX_VALID : $this::VAR_REGEX_VALID, $var);
    }

    /**
     * Convert var to name.
     *
     * @since 160220 Initial release.
     *
     * @param string $var Var.
     *
     * @return string Name; based on var.
     */
    public function toName(string $var): string
    {
        $name        = $var; // Initialize.
        $name        = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $name);
        $name        = $this->c::mbUcWords($name);
        return $name = $this->c::mbTrim($name);
    }

    /**
     * Convert var to acronym.
     *
     * @since 160220 Initial release.
     *
     * @param string $var    Var.
     * @param bool   $strict Strict?
     *
     * @return string Acronym; based on var.
     */
    public function toAcronym(string $var, bool $strict = true): string
    {
        return $this->c::nameToAcronym($this->toName($var), $strict);
    }

    /**
     * Convert var to slug.
     *
     * @since 160220 Initial release.
     *
     * @param string $var    Var.
     * @param bool   $strict Strict?
     *
     * @return string Slug; based on var.
     */
    public function toSlug(string $var, bool $strict = true): string
    {
        $slug = $var; // Initialize.
        $slug = mb_strtolower($this->c::forceAscii($slug));
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);
        $slug = $this->c::mbTrim($slug, '', '-');

        if ($strict && $slug && !preg_match('/^[a-z]/u', $slug)) {
            $slug = 'x'.$slug; // Force `^[a-z]`.
        }
        return $slug;
    }

    /**
     * Convert var to camelCase.
     *
     * @since 170413.34876 Initial release.
     *
     * @param string $var    Var.
     * @param bool   $strict Strict?
     *
     * @return string camelCase; based on var.
     */
    public function toCamelCase(string $var, bool $strict = true): string
    {
        $var = mb_strtolower($this->c::forceAscii($var));
        $var = preg_replace('/[^a-z0-9]+/u', '_', $var);
        $var = $this->c::mbTrim($var, '', '_');

        if ($strict && $var && !preg_match('/^[a-z]/u', $var)) {
            $var = 'x'.$var; // Force `^[a-z]`.
        }
        return $camelCase = $var ? preg_replace_callback('/_(.)/u', function (array $m): string {
            return mb_strtoupper($m[1]); // e.g., `_x` becomes `X` for camelCase.
        }, $var) : $var; // Converts variable into camelCase variable.
    }
}
