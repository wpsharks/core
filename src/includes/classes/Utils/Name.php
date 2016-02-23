<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Name utilities.
 *
 * @since 150424 Initial release.
 */
class Name extends Classes\Core
{
    /**
     * Strip prefixes/suffixes.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name Full name.
     *
     * @return string Name w/o prefixes/suffixes.
     */
    protected function stripClean(string $name)
    {
        static $last_in, $last_out;

        if ($name === $last_in && isset($last_out)) {
            return $last_out;
        } else {
            $last_in = $name;
        }
        if (!isset($name[0])) {
            return $last_out = $name;
        }
        $name = str_replace('"', '', $name);
        $name = preg_replace('/^(?:Mr\.?|Mrs\.?|Ms\.?|Dr\.?)\s+/ui', '', $name);
        $name = preg_replace('/\s+(?:Sr\.?|Jr\.?|IV|I+)$/ui', '', $name);
        $name = preg_replace('/\s+/u', ' ', $name);
        $name = c\mb_trim($name);

        return $last_out = $name;
    }

    /**
     * First from full name.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name  Full name.
     * @param string $email Fallback on email?
     *
     * @return string First name.
     */
    public function firstIn(string $name, string $email = ''): string
    {
        $name = $this->stripClean($name);

        if ($name && mb_strpos($name, ' ', 1) !== false) {
            return explode(' ', $name, 2)[0];
        } elseif (!$name && $email && mb_strpos($email, '@', 1) !== false) {
            return c\mb_ucfirst(explode('@', $email, 2)[0]);
        } else {
            return $name;
        }
    }

    /**
     * Last from full name.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name Full name.
     *
     * @return string Last name.
     */
    public function lastIn(string $name): string
    {
        $name = $this->stripClean($name);

        if ($name && mb_strpos($name, ' ', 1) !== false) {
            return explode(' ', $name, 2)[1];
        } else {
            return $name;
        }
    }

    /**
     * Convert name to acronym.
     *
     * @since 160220 Initial release.
     *
     * @param string $name Full name.
     *
     * @return string Acronym; based on name.
     */
    public function toAcronym(string $name): string
    {
        $acronym = ''; // Initialize.

        $name = $this->stripClean($name);
        $name = c\force_ascii($name); // ASCII only.
        $name = preg_replace('/([a-z])([A-Z0-9])/u', '${1} ${2}', $name);
        // This breaks `s2` into `s 2` and `xCache` into `x Cache`.

        foreach (preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) as $_word) {
            if (isset($_word[0]) && ctype_alnum($_word[0])) {
                $acronym .= $_word[0];
            }
        } // unset($_word);

        if (mb_strlen($acronym) < 2) {
            $acronym = c\mb_str_pad(mb_substr($acronym, 0, 2), 2, 'x');
        }
        return mb_strtoupper($acronym);
    }

    /**
     * Convert name to slug.
     *
     * @since 150424 Initial release.
     *
     * @param string $name Full name.
     *
     * @return string Slug; based on name.
     */
    public function toSlug(string $name): string
    {
        $name = $this->stripClean($name);

        $slug = $name; // Working copy.
        $slug = mb_strtolower(c\force_ascii($slug));
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);
        $slug = c\mb_trim($slug, '', '-');

        if ($slug && !preg_match('/^[a-z]/u', $slug)) {
            $slug = 'x'.$slug; // Force `^[a-z]`.
        }
        return $slug;
    }

    /**
     * Convert name to var.
     *
     * @since 160220 Var utils.
     *
     * @param string $name Full name.
     *
     * @return string Var; based on name.
     */
    public function toVar(string $name): string
    {
        $name = $this->stripClean($name);

        $var = $name; // Working copy.
        $var = mb_strtolower(c\force_ascii($var));
        $var = preg_replace('/[^a-z0-9]+/u', '_', $var);
        $var = c\mb_trim($var, '', '_');

        if ($var && !preg_match('/^[a-z]/u', $var)) {
            $var = 'x'.$var; // Force `^[a-z]`.
        }
        return $var;
    }
}
