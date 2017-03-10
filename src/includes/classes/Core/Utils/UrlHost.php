<?php
/**
 * URL host utilities.
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
     * @since 170124.74961 Adding `port` key.
     * @since 170124.74961 Adding `root_name` key.
     * @since 170124.74961 Adding `root_port` key.
     * @since 170124.74961 Removing `root_as_name` key.
     * @since 170124.74961 The `name` key now contains only the name.
     * @since 170124.74961 The `root` key now contains a full root host (including port).
     * @since 170124.74961 Bug fix. Hosts with ports in their name are now exploded properly.
     *
     * @param string $host The input host to parse.
     *
     * @return array `[name, port, sub, subs, root, root_name, root_port, root_basename, tld]`.
     *
     * @internal This allows an empty `$host` so that a caller can get the array elements even if empty.
     */
    public function parse(string $host, bool $no_port = false): array
    {
        $host = mb_strtolower($host); // `abc.xyz.example.com:80`.

        $name = preg_replace('/\:[0-9]+$/u', '', $host); // `abc.xyz.example.com`.
        $port = (explode(':', $host, 2) + ['', ''])[1]; // `80`; i.e., port number.

        $name_parts = explode('.', $name); // `[abc,xyz,example,com]`.

        $subs = array_slice($name_parts, 0, -2); // `[abc,xyz]`.
        $sub  = implode('.', $subs); // `abc.xyz`.

        $root_name     = implode('.', array_slice($name_parts, -2)); // `example.com`.
        $root_port     = $port; // This is nothing more than a copy of the `$port`; `80`.
        $root          = $root_name.(isset($root_port[0]) ? ':'.$root_port : ''); // `example.com:80`.
        $root_basename = implode('.', array_slice($name_parts, -2, 1)); // `example`.

        $tld = implode('.', array_slice($name_parts, -1)); // `com`, `net`, `org`, etc.

        return compact('name', 'port', 'sub', 'subs', 'root', 'root_name', 'root_port', 'root_basename', 'tld');
    }

    /**
     * Unparses a host.
     *
     * @since 151002 Adding host parser.
     * @since 170124.74961 Collecting both `name` and `port` now.
     *
     * @param array $parts Input host parts.
     *
     * @return string Unparsed host string.
     */
    public function unParse(array $parts): string
    {
        $name = $port= '';

        if (isset($parts['name'][0])) {
            $name = $parts['name'];
        }
        if (isset($parts['port'][0])) {
            $port = ':'.$parts['port'];
        }
        return $name.$port;
    }
}
