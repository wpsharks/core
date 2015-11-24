<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Name utilities.
 *
 * @since 150424 Initial release.
 */
class Name extends Classes\AbsBase
{
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
    public function firstFrom(string $name, string $email = ''): string
    {
        $name = $this->stripClean($name);

        if ($name && mb_strpos($name, ' ', 1) !== false) {
            return explode(' ', $name, 2)[0];
        } elseif (!$name && $email && mb_strpos($email, '@', 1) !== false) {
            return $this->Utils->UcFirst(explode('@', $email, 2)[0]);
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
    public function lastFrom(string $name): string
    {
        $name = $this->stripClean($name);

        if ($name && mb_strpos($name, ' ', 1) !== false) {
            return explode(' ', $name, 2)[1];
        } else {
            return $name;
        }
    }

    /**
     * Strip prefixes/suffixes.
     *
     * @since 151121 Adding name utilities.
     *
     * @param string $name Full name.
     *
     * @return string Name w/o prefixes/suffixes.
     */
    public function stripClean(string $name)
    {
        static $last_in, $last_out; // Cache.

        if ($name === $last_in && isset($last_out)) {
            return $last_out; // Did this already.
        } else {
            $last_in = $name; // Remember.
        }
        if (!isset($name[0])) {
            return ($last_out = $name);
        }
        $name = str_replace('"', '', $name);
        $name = preg_replace('/^(?:Mr\.?|Mrs\.?|Ms\.?|Dr\.?)\s+/ui', '', $name);
        $name = preg_replace('/\s+(?:Sr\.?|Jr\.?|IV|I+)$/ui', '', $name);
        $name = preg_replace('/\s+/u', ' ', $name);
        $name = $this->Utils->Trim($name);

        return ($last_out = $name);
    }
}
