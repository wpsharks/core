<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * URL host utilities.
 *
 * @since 151002 Adding host parser.
 */
class UrlHost extends Classes\Core\Base\Core
{
    /**
     * Host parser.
     *
     * @since 151002 Adding host parser.
     *
     * @param string $host    The input host to parse.
     * @param bool   $no_port No port number? Defaults to `FALSE`.
     *
     * @note Some hosts include a port number in `$_SERVER['HTTP_HOST']`.
     *    That SHOULD be left intact for URL generation in almost every scenario.
     *    However, in a few other edge cases it may be desirable to exclude the port number.
     *    e.g., if the purpose of obtaining the host is to use it for email generation, or in a slug, etc.
     *
     * @return array Host parts; i.e., `name`, `subs`, `sub`, `root`, `root_basename`, `root_as_name`, `tld`.
     *
     * @note This allows an empty `$host` so that a caller can get the array elements even if empty.
     */
    public function parse(string $host, bool $no_port = false): array
    {
        if ($no_port) {
            $host = preg_replace('/\:[0-9]+$/u', '', $host);
        }
        $name  = mb_strtolower($host); // `abc.xyz.example.com`
        $parts = explode('.', $name); // `[abc,xyz,example,com]`

        $subs = array_slice($parts, 0, -2); // `[abc,xyz]`
        $sub  = implode('.', $subs); // `abc.xyz`

        $root          = implode('.', array_slice($parts, -2)); // `example.com`
        $root_basename = implode('.', array_slice($parts, -2, 1)); // `example`
        $root_as_name  = $this->c::slugToName($root_basename); // `Example`

        $tld = implode('.', array_slice($parts, -1)); // `com`

        return compact('name', 'subs', 'sub', 'root', 'root_basename', 'root_as_name', 'tld');
    }

    /**
     * Unparses a host.
     *
     * @since 151002 Adding host parser.
     *
     * @param array $parts Input host parts.
     *
     * @return string Unparsed host string.
     */
    public function unParse(array $parts): string
    {
        return !empty($parts['name']) ? (string) $parts['name'] : '';
    }
}
